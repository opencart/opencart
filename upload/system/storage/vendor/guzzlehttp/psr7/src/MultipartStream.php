<?php

declare(strict_types=1);

namespace GuzzleHttp\Psr7;

use Psr\Http\Message\StreamInterface;

/**
 * Stream that when read returns bytes for a streaming multipart or
 * multipart/form-data stream.
 */
final class MultipartStream implements StreamInterface
{
    use StreamDecoratorTrait;

    /** @var string */
    private $boundary;

    /** @var StreamInterface */
    private $stream;

    /**
     * @param array  $elements Array of associative arrays, each containing a
     *                         required "name" key mapping to the form field,
     *                         name, a required "contents" key mapping to any
     *                         value accepted by Utils::streamFor() (scalar,
     *                         null, resource, StreamInterface, Iterator, or
     *                         callable), or an array for nested expansion.
     *                         Optional keys include "headers" (associative
     *                         array of custom headers) and "filename" (string
     *                         to send as the filename in the part).
     *                         When "contents" is an array, it is recursively
     *                         expanded into multiple fields using bracket notation
     *                         (e.g., name[0][key]). Empty arrays produce no fields.
     *                         The "filename" and "headers" options cannot be used
     *                         with array contents.
     * @param string $boundary You can optionally provide a specific boundary
     *
     * @throws \InvalidArgumentException
     */
    public function __construct(array $elements = [], ?string $boundary = null)
    {
        $this->boundary = $boundary ?: bin2hex(random_bytes(20));
        $this->stream = $this->createStream($elements);
    }

    public function getBoundary(): string
    {
        return $this->boundary;
    }

    public function isWritable(): bool
    {
        return false;
    }

    /**
     * Get the headers needed before transferring the content of a POST file
     *
     * @param string[] $headers
     */
    private function getHeaders(array $headers): string
    {
        $str = '';
        foreach ($headers as $key => $value) {
            $str .= "{$key}: {$value}\r\n";
        }

        return "--{$this->boundary}\r\n".trim($str)."\r\n\r\n";
    }

    /**
     * Create the aggregate stream that will be used to upload the POST data
     */
    protected function createStream(array $elements = []): StreamInterface
    {
        $stream = new AppendStream();

        foreach ($elements as $element) {
            if (!is_array($element)) {
                throw new \UnexpectedValueException('An array is expected');
            }
            $this->addElement($stream, $element);
        }

        // Add the trailing boundary with CRLF
        $stream->addStream(Utils::streamFor("--{$this->boundary}--\r\n"));

        return $stream;
    }

    private function addElement(AppendStream $stream, array $element): void
    {
        foreach (['contents', 'name'] as $key) {
            if (!array_key_exists($key, $element)) {
                throw new \InvalidArgumentException("A '{$key}' key is required");
            }
        }

        if (!is_string($element['name']) && !is_int($element['name'])) {
            throw new \InvalidArgumentException("The 'name' key must be a string or integer");
        }

        if (is_array($element['contents'])) {
            if (array_key_exists('filename', $element) || array_key_exists('headers', $element)) {
                throw new \InvalidArgumentException(
                    "The 'filename' and 'headers' options cannot be used when 'contents' is an array"
                );
            }

            $this->addNestedElements($stream, $element['contents'], (string) $element['name']);

            return;
        }

        $element['contents'] = Utils::streamFor($element['contents']);

        if (empty($element['filename'])) {
            $uri = $element['contents']->getMetadata('uri');
            if ($uri && \is_string($uri) && \substr($uri, 0, 6) !== 'php://' && \substr($uri, 0, 7) !== 'data://') {
                $element['filename'] = $uri;
            }
        }

        [$body, $headers] = $this->createElement(
            (string) $element['name'],
            $element['contents'],
            $element['filename'] ?? null,
            $element['headers'] ?? []
        );

        $stream->addStream(Utils::streamFor($this->getHeaders($headers)));
        $stream->addStream($body);
        $stream->addStream(Utils::streamFor("\r\n"));
    }

    /**
     * Recursively expand array contents into multiple form fields.
     *
     * @param array<array-key, mixed> $contents
     */
    private function addNestedElements(AppendStream $stream, array $contents, string $root): void
    {
        foreach ($contents as $key => $value) {
            $fieldName = $root === '' ? sprintf('[%s]', (string) $key) : sprintf('%s[%s]', $root, (string) $key);

            if (is_array($value)) {
                $this->addNestedElements($stream, $value, $fieldName);
            } else {
                $this->addElement($stream, ['name' => $fieldName, 'contents' => $value]);
            }
        }
    }

    /**
     * @param string[] $headers
     *
     * @return array{0: StreamInterface, 1: string[]}
     */
    private function createElement(string $name, StreamInterface $stream, ?string $filename, array $headers): array
    {
        // Set a default content-disposition header if one was no provided
        $disposition = self::getHeader($headers, 'content-disposition');
        if (!$disposition) {
            $headers['Content-Disposition'] = ($filename === '0' || $filename)
                ? sprintf(
                    'form-data; name="%s"; filename="%s"',
                    $name,
                    basename($filename)
                )
                : "form-data; name=\"{$name}\"";
        }

        // Set a default content-length header if one was no provided
        $length = self::getHeader($headers, 'content-length');
        if (!$length) {
            if ($length = $stream->getSize()) {
                $headers['Content-Length'] = (string) $length;
            }
        }

        // Set a default Content-Type if one was not supplied
        $type = self::getHeader($headers, 'content-type');
        if (!$type && ($filename === '0' || $filename)) {
            $headers['Content-Type'] = MimeType::fromFilename($filename) ?? 'application/octet-stream';
        }

        return [$stream, $headers];
    }

    /**
     * @param string[] $headers
     */
    private static function getHeader(array $headers, string $key): ?string
    {
        $lowercaseHeader = strtolower($key);
        foreach ($headers as $k => $v) {
            if (strtolower((string) $k) === $lowercaseHeader) {
                return $v;
            }
        }

        return null;
    }
}
