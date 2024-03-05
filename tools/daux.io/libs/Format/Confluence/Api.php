<?php namespace Todaymade\Daux\Format\Confluence;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\BadResponseException;

class Api
{
    protected $baseUrl;
    protected $user;
    protected $pass;

    protected $space;

    public function __construct($baseUrl, $user, $pass)
    {
        $this->baseUrl = $baseUrl;
        $this->user = $user;
        $this->pass = $pass;
    }

    public function setSpace($spaceId)
    {
        $this->space = $spaceId;
    }

    /**
     * This method is public due to test purposes.
     *
     * @return Client
     */
    public function getClient()
    {
        $options = [
            'base_uri' => $this->baseUrl . 'rest/api/',
            'auth' => [$this->user, $this->pass],
        ];

        return new Client($options);
    }

    /**
     * The standard error message from guzzle is quite poor in informations,
     * this will give little bit more sense to it and return it.
     *
     * @return \Exception
     */
    protected function handleError(BadResponseException $e)
    {
        $request = $e->getRequest();
        $response = $e->getResponse();

        $level = floor($response->getStatusCode() / 100);

        if ($level == '4') {
            $label = 'Client error response';
        } elseif ($level == '5') {
            $label = 'Server error response';
        } else {
            $label = 'Unsuccessful response';
        }

        $message = $label .
            "\n [url] " . $request->getUri() .
            "\n [status code] " . $response->getStatusCode() .
            "\n [message] ";

        $body = $response->getBody();
        $json = json_decode($body, true);
        $hasMessage = $json != null && array_key_exists('message', $json) && !empty($json['message']);
        $message .= $hasMessage ? $json['message'] : $body;

        if ($level == '4' && strpos($message, 'page with this title already exists') !== false) {
            return new DuplicateTitleException($message, 0, $e->getPrevious());
        }

        return new BadResponseException($message, $request, $response, $e->getPrevious());
    }

    public function getPage($id)
    {
        $url = "content/$id?expand=space,ancestors,version,body.storage";

        try {
            $result = json_decode($this->getClient()->get($url)->getBody(), true);
        } catch (BadResponseException $e) {
            throw $this->handleError($e);
        }

        $ancestorId = null;
        if (array_key_exists('ancestors', $result) && count($result['ancestors'])) {
            $ancestorPage = end($result['ancestors']); // We need the direct parent
            $ancestorId = $ancestorPage['id'];
        }

        return [
            'id' => $result['id'],
            'space_key' => $result['space']['key'],
            'ancestor_id' => $ancestorId,
            'title' => $result['title'],
            'version' => $result['version']['number'],
            'content' => $result['body']['storage']['value'],
        ];
    }

    /**
     * Get a list of pages.
     *
     * @param int $rootPage
     * @param bool $recursive
     *
     * @return array
     */
    public function getList($rootPage, $recursive = false)
    {
        $increment = 15;

        // We set a limit of 15 as it appears that
        // Confluence fails silently when retrieving
        // more than 20 entries with "body.storage"
        $baseUrl = $url = "content/$rootPage/child/page?expand=space,version,body.storage&limit=$increment";
        $start = 0;

        $pages = [];

        do {
            try {
                $hierarchy = json_decode($this->getClient()->get($url)->getBody(), true);
            } catch (BadResponseException $e) {
                throw $this->handleError($e);
            }

            foreach ($hierarchy['results'] as $result) {
                $pages[$result['title']] = [
                    'id' => $result['id'],
                    'ancestor_id' => $rootPage,
                    'space_key' => $result['space']['key'],
                    'title' => $result['title'],
                    'version' => $result['version']['number'],
                    'content' => $result['body']['storage']['value'],
                ];

                if ($recursive) {
                    $pages[$result['title']]['children'] = $this->getList($result['id'], true);
                }
            }

            // We don't use _links->next as after ~30 elements
            // it doesn't show any new elements. This seems
            // to be a bug in Confluence
            $start += $increment;
            $url = "$baseUrl&start=$start";
        } while (!empty($hierarchy['results']));

        return $pages;
    }

    /**
     * @param int $parentId
     * @param string $title
     * @param string $content
     *
     * @return int
     */
    public function createPage($parentId, $title, $content)
    {
        $body = [
            'type' => 'page',
            'space' => ['key' => $this->space],
            'title' => $title,
            'body' => ['storage' => ['value' => $content, 'representation' => 'storage']],
        ];

        if ($parentId) {
            $body['ancestors'] = [['type' => 'page', 'id' => $parentId]];
        }

        try {
            $response = json_decode($this->getClient()->post('content', ['json' => $body])->getBody(), true);
        } catch (BadResponseException $e) {
            throw $this->handleError($e);
        }

        return $response['id'];
    }

    /**
     * @param int $parentId
     * @param int $pageId
     * @param int $newVersion
     * @param string $title
     * @param string $content
     */
    public function updatePage($parentId, $pageId, $newVersion, $title, $content)
    {
        $body = [
            'type' => 'page',
            'space' => ['key' => $this->space],
            'version' => ['number' => $newVersion, 'minorEdit' => true],
            'title' => $title,
            'body' => ['storage' => ['value' => $content, 'representation' => 'storage']],
        ];

        if ($parentId) {
            $body['ancestors'] = [['type' => 'page', 'id' => $parentId]];
        }

        try {
            $this->getClient()->put("content/$pageId", ['json' => $body]);
        } catch (BadResponseException $e) {
            $error = $this->handleError($e);

            $re = '/\[(\d*),(\d*)\]$/';
            preg_match($re, $error->getMessage(), $matches, PREG_OFFSET_CAPTURE, 0);

            if (count($matches) == 3) {
                echo "\nContent: \n";
                echo $this->showSourceCode($content, $matches[1][0], $matches[2][0]);
            }

            throw $error;
        }
    }

    public function showSourceCode($css, $lineNumber, $column)
    {
        $lines = preg_split("/\r?\n/", $css);

        if ($lines === false) {
            return $css;
        }

        $start = max($lineNumber - 3, 0);
        $end = min($lineNumber + 2, count($lines));

        $maxWidth = strlen("$end");

        $filtered = array_slice($lines, $start, $end - $start);

        $prepared = [];
        foreach ($filtered as $index => $line) {
            $number = $start + 1 + $index;
            $gutter = substr(' ' . (' ' . $number), -$maxWidth) . ' | ';

            if ($number == $lineNumber) {
                $spacing = str_repeat(' ', strlen($gutter) + $column - 2);
                $prepared[] = '>' . $gutter . $line . "\n " . $spacing . '^';
            } else {
                $prepared[] = ' ' . $gutter . $line;
            }
        }

        return implode("\n", $prepared);
    }

    /**
     * Delete a page.
     *
     * @param int $pageId
     *
     * @return mixed
     */
    public function deletePage($pageId)
    {
        try {
            return json_decode($this->getClient()->delete('content/' . $pageId)->getBody(), true);
        } catch (BadResponseException $e) {
            throw $this->handleError($e);
        }
    }

    private function getAttachment($id, $attachment)
    {
        // Check if an attachment with
        // this name is uploaded
        try {
            $url = "content/$id/child/attachment?filename=" . urlencode($attachment['filename']);

            return json_decode($this->getClient()->get($url)->getBody(), true);
        } catch (BadResponseException $e) {
            throw $this->handleError($e);
        }
    }

    private function putAttachment($url, $attachment)
    {
        $contents = array_key_exists('file', $attachment)
            ? fopen($attachment['file']->getPath(), 'r')
            : $attachment['content'];

        try {
            $this->getClient()->post(
                $url,
                [
                    'multipart' => [['name' => 'file', 'contents' => $contents, 'filename' => $attachment['filename']]],
                    'headers' => ['X-Atlassian-Token' => 'nocheck'],
                ]
            );
        } catch (BadResponseException $e) {
            throw $this->handleError($e);
        }
    }

    private function getFileSize($attachment)
    {
        if (array_key_exists('file', $attachment)) {
            return filesize($attachment['file']->getPath());
        }

        if (function_exists('mb_strlen')) {
            return mb_strlen($attachment['content']);
        }

        return strlen($attachment['content']);
    }

    /**
     * @param int $id
     * @param array $attachment
     * @param callable $write Write output to the console
     */
    public function uploadAttachment($id, $attachment, $write)
    {
        $result = $this->getAttachment($id, $attachment);

        $url = "content/$id/child/attachment";

        // If the attachment is already uploaded,
        // the update URL is different
        if (count($result['results'])) {
            if ($this->getFileSize($attachment) == $result['results'][0]['extensions']['fileSize']) {
                $write(' ( An attachment of the same size already exists, skipping. )');

                return;
            }

            $url .= "/{$result['results'][0]['id']}/data";
        }

        $this->putAttachment($url, $attachment);
    }
}
