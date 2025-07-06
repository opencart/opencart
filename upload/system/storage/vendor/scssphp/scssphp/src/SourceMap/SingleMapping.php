<?php

/**
 * SCSSPHP
 *
 * @copyright 2018-2020 Anthon Pang
 *
 * @license http://opensource.org/licenses/MIT MIT
 *
 * @link http://scssphp.github.io/scssphp
 */

namespace ScssPhp\ScssPhp\SourceMap;

use ScssPhp\ScssPhp\SourceMap\Builder\Entry;
use SourceSpan\FileLocation;
use SourceSpan\SourceFile;

/**
 * @internal
 */
final class SingleMapping
{
    /**
     * @var list<string>
     */
    public readonly array $urls;

    /**
     * The {@see SourceFile}s to which the entries in {@see $lines} refer.
     *
     * This is in the same order as {@see $urls}. If this was constructed using
     * {@see SingleMapping::fromEntries()}, this contains files from any {@see FileLocation}s
     * used to build the mapping.
     *
     * Files whose contents aren't available are `null`.
     *
     * @var list<SourceFile|null>
     */
    public readonly array $files;

    /**
     * Entries indicating the beginning of each span.
     *
     * @var list<TargetLineEntry>
     */
    public readonly array $lines;

    /**
     * Url of the target file.
     */
    public ?string $targetUrl = null;

    /**
     * Source root prepended to all entries in {@see $urls}.
     */
    public ?string $sourceRoot = null;

    /**
     * @param list<SourceFile|null> $files
     * @param list<string> $urls
     * @param list<TargetLineEntry> $lines
     */
    private function __construct(array $files, array $urls, array $lines)
    {
        $this->urls = $urls;
        $this->files = $files;
        $this->lines = $lines;
    }

    /**
     * @param Entry[] $sourceEntries
     */
    public static function fromEntries(array $sourceEntries): self
    {
        usort($sourceEntries, fn (Entry $a, Entry $b) => $a->compareTo($b));

        $lines = [];
        // Indices associated with file urls that will be part of the source map. We
        // rely on map order so that `array_keys($url)[$urls[$u]] === $u`
        $urls = [];
        // The file for each URL, indexed by $urls' values.
        $files = [];
        $lineNum = null;
        $targetEntries = null;

        foreach ($sourceEntries as $sourceEntry) {
            if ($lineNum === null || $sourceEntry->target->getLine() > $lineNum) {
                $lineNum = $sourceEntry->target->getLine();
                $targetEntries = new \ArrayObject();
                $lines[] = new TargetLineEntry($lineNum, $targetEntries);
            }

            $sourceUrl = $sourceEntry->source->getSourceUrl();
            $urlId = $urls[$sourceUrl?->toString() ?? ''] ??= \count($urls);

            if ($sourceEntry->source instanceof FileLocation) {
                $files[$urlId] ??= $sourceEntry->source->getFile();
            }

            $targetEntries[] = new TargetEntry($sourceEntry->target->getColumn(), $urlId, $sourceEntry->source->getLine(), $sourceEntry->source->getColumn());
        }

        return new self(array_values(array_map(fn (int $i) => $files[$i] ?? null, $urls)), array_keys($urls), $lines);
    }

    /**
     * Encodes the Mapping mappings as a json map.
     *
     * If $includeSourceContents is `true`, this includes the source file
     * contents from {@see $files} in the map if possible.
     *
     * @return array<string, mixed>
     */
    public function toJson(bool $includeSourceContents = false): array
    {
        $buff = '';
        $line = 0;
        $column = 0;
        $srcLine = 0;
        $srcColumn = 0;
        $srcUrlId = 0;
        $first = true;

        foreach ($this->lines as $entry) {
            $nextLine = $entry->line;

            if ($nextLine > $line) {
                for ($i = $line; $i < $nextLine; $i++) {
                    $buff .= ';';
                }
                $line = $nextLine;
                $column = 0;
                $first = true;
            }

            foreach ($entry->entries as $segment) {
                if (!$first) {
                    $buff .= ',';
                }
                $first = false;
                $buff .= Base64VLQ::encode($segment->column - $column);
                $column = $segment->column;

                // Encoding can be just the column offset if there is no source
                // information.
                $newUrlId = $segment->sourceUrlId;
                if ($newUrlId === null) {
                    continue;
                }
                \assert($segment->sourceLine !== null);
                \assert($segment->sourceColumn !== null);

                $buff .= Base64VLQ::encode($newUrlId - $srcUrlId);
                $srcUrlId = $newUrlId;
                $buff .= Base64VLQ::encode($segment->sourceLine - $srcLine);
                $srcLine = $segment->sourceLine;
                $buff .= Base64VLQ::encode($segment->sourceColumn - $srcColumn);
                $srcColumn = $segment->sourceColumn;
            }
        }

        $result = [
            'version' => 3,
            'sourceRoot' => $this->sourceRoot ?? '',
            'sources' => $this->urls,
            'names' => [],
            'mappings' => $buff,
        ];

        if ($this->targetUrl !== null) {
            $result['file'] = $this->targetUrl;
        }

        if ($includeSourceContents) {
            $result['sourcesContent'] = array_map(fn (?SourceFile $file) => $file?->getText(0), $this->files);
        }

        return $result;
    }

    /**
     * Returns a new mapping with {@see $urls} transformed by $callback.
     *
     * @param callable(string): string $callback
     */
    public function mapUrls(callable $callback): self
    {
        $newUrls = array_map($callback, $this->urls);

        $new = new self($this->files, $newUrls, $this->lines);
        $new->targetUrl = $this->targetUrl;
        $new->sourceRoot = $this->sourceRoot;

        return $new;
    }
}
