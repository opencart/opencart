<?php
namespace GuzzleHttp\Subscriber\Log;

use GuzzleHttp\Message\MessageInterface;
use GuzzleHttp\Message\RequestInterface;
use GuzzleHttp\Message\ResponseInterface;

/**
 * Formats log messages using variable substitutions for requests, responses,
 * and other transactional data.
 *
 * The following variable substitutions are supported:
 *
 * - {request}:      Full HTTP request message
 * - {response}:     Full HTTP response message
 * - {ts}:           Timestamp
 * - {host}:         Host of the request
 * - {method}:       Method of the request
 * - {url}:          URL of the request
 * - {host}:         Host of the request
 * - {protocol}:     Request protocol
 * - {version}:      Protocol version
 * - {resource}:     Resource of the request (path + query + fragment)
 * - {hostname}:     Hostname of the machine that sent the request
 * - {code}:         Status code of the response (if available)
 * - {phrase}:       Reason phrase of the response  (if available)
 * - {error}:        Any error messages (if available)
 * - {req_header_*}: Replace `*` with the lowercased name of a request header to add to the message
 * - {res_header_*}: Replace `*` with the lowercased name of a response header to add to the message
 * - {req_headers}:  Request headers
 * - {res_headers}:  Response headers
 * - {req_body}:     Request body
 * - {res_body}:     Response body
 */
class Formatter
{
    /**
     * Apache Common Log Format.
     * @link http://httpd.apache.org/docs/1.3/logs.html#common
     * @var string
     */
    const CLF = "{hostname} {req_header_User-Agent} - [{ts}] \"{method} {resource} {protocol}/{version}\" {code} {res_header_Content-Length}";
    const DEBUG = ">>>>>>>>\n{request}\n<<<<<<<<\n{response}\n--------\n{error}";
    const SHORT = '[{ts}] "{method} {resource} {protocol}/{version}" {code}';

    /** @var string Template used to format log messages */
    private $template;

    /**
     * @param string $template Log message template
     */
    public function __construct($template = self::CLF)
    {
        $this->template = $template ?: self::CLF;
    }

    /**
     * Returns a formatted message
     *
     * @param RequestInterface  $request    Request that was sent
     * @param ResponseInterface $response   Response that was received
     * @param \Exception        $error      Exception that was received
     * @param array             $customData Associative array of custom template data
     *
     * @return string
     */
    public function format(
        RequestInterface $request,
        ResponseInterface $response = null,
        \Exception $error = null,
        array $customData = []
    ) {
        $cache = $customData;

        return preg_replace_callback(
            '/{\s*([A-Za-z_\-\.0-9]+)\s*}/',
            function (array $matches) use ($request, $response, $error, &$cache) {

                if (isset($cache[$matches[1]])) {
                    return $cache[$matches[1]];
                }

                $result = '';
                switch ($matches[1]) {
                    case 'request':
                        $result = $request;
                        break;
                    case 'response':
                        $result = $response;
                        break;
                    case 'req_headers':
                        $result = trim($request->getMethod() . ' '
                            . $request->getResource()) . ' HTTP/'
                            . $request->getProtocolVersion() . "\r\n"
                            . $this->headers($request);
                        break;
                    case 'res_headers':
                        $result = $response ?
                            sprintf(
                                'HTTP/%s %d %s',
                                $response->getProtocolVersion(),
                                $response->getStatusCode(),
                                $response->getReasonPhrase()
                            ) . "\r\n" . $this->headers($response)
                            : 'NULL';
                        break;
                    case 'req_body':
                        $result = $request->getBody();
                        break;
                    case 'res_body':
                        $result = $response ? $response->getBody() : 'NULL';
                        break;
                    case 'ts':
                        $result = gmdate('c');
                        break;
                    case 'method':
                        $result = $request->getMethod();
                        break;
                    case 'url':
                        $result = $request->getUrl();
                        break;
                    case 'resource':
                        $result = $request->getResource();
                        break;
                    case 'req_version':
                        $result = $request->getProtocolVersion();
                        break;
                    case 'res_version':
                        $result = $response
                            ? $response->getProtocolVersion()
                            : 'NULL';
                        break;
                    case 'host':
                        $result = $request->getHost();
                        break;
                    case 'hostname':
                        $result = gethostname();
                        break;
                    case 'code':
                        $result = $response
                            ? $response->getStatusCode()
                            : 'NULL';
                        break;
                    case 'phrase':
                        $result = $response
                            ? $response->getReasonPhrase()
                            : 'NULL';
                        break;
                    case 'error':
                        $result = $error ? $error->getMessage() : 'NULL';
                        break;
                    default:
                        // handle prefixed dynamic headers
                        if (strpos($matches[1], 'req_header_') === 0) {
                            $result = $request->getHeader(substr($matches[1], 11));
                        } elseif (strpos($matches[1], 'res_header_') === 0) {
                            $result = $response
                                ? $response->getHeader(substr($matches[1], 11))
                                : 'NULL';
                        }
                }

                $cache[$matches[1]] = $result;
                return $result;
            },
            $this->template
        );
    }

    private function headers(MessageInterface $message)
    {
        $result = '';
        foreach ($message->getHeaders() as $name => $values) {
            $result .= $name . ': ' . implode(', ', $values) . "\r\n";
        }

        return trim($result);
    }
}
