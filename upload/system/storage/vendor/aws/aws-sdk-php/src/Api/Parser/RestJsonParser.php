<?php
namespace Aws\Api\Parser;

use Aws\Api\Service;
use Aws\Api\StructureShape;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\StreamInterface;

/**
 * @internal Implements REST-JSON parsing (e.g., Glacier, Elastic Transcoder)
 */
class RestJsonParser extends AbstractRestParser
{
    use PayloadParserTrait;

    /**
     * @param Service    $api    Service description
     * @param JsonParser $parser JSON body builder
     */
    public function __construct(Service $api, ?JsonParser $parser = null)
    {
        parent::__construct($api);
        $this->parser = $parser ?: new JsonParser();
    }

    protected function payload(
        ResponseInterface $response,
        StructureShape $member,
        array &$result
    ) {
        $responseBody = (string) $response->getBody();

        // Parse JSON if we have content
        $parsedJson = null;
        if (!empty($responseBody)) {
            $parsedJson = $this->parseJson($responseBody, $response);
        } else {
            // An empty response body should be deserialized as null
            $result = $parsedJson;
            return;
        }

        $parsedBody = $this->parser->parse($member, $parsedJson);
        if (is_string($parsedBody) && $member['document']) {
            // Document types can be strings: replace entire result
            $result = $parsedBody;
        } else {
            // Merge array/object results into existing result
            $result = array_merge($result, (array) $parsedBody);
        }
    }

    public function parseMemberFromStream(
        StreamInterface $stream,
        StructureShape $member,
        $response
    ) {
        $jsonBody = $this->parseJson($stream, $response);
        if ($jsonBody) {
            return $this->parser->parse($member, $jsonBody);
        }
        return [];
    }
}
