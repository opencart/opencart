<?php namespace Todaymade\Daux\Server;

use Symfony\Component\Mime\MimeTypeGuesserInterface;

/**
 * Guesses the mime type using the file's extension.
 */
class ExtensionMimeTypeGuesser implements MimeTypeGuesserInterface
{
    /**
     * {@inheritdoc}
     */
    public function isGuesserSupported(): bool
    {
        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function guessMimeType(string $path): ?string
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        if ($extension == 'css') {
            return 'text/css';
        }

        if ($extension == 'js') {
            return 'application/javascript';
        }

        return null;
    }
}
