<?php
namespace GuzzleHttp\Subscriber\Log;

use GuzzleHttp\Event\RequestEvents;
use GuzzleHttp\Event\SubscriberInterface;
use GuzzleHttp\Event\CompleteEvent;
use GuzzleHttp\Event\ErrorEvent;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

/**
 * Plugin class that will add request and response logging to an HTTP request.
 *
 * The log plugin uses a message formatter that allows custom messages via
 * template variable substitution.
 *
 * @see MessageLogger for a list of available template variable substitutions
 */
class LogSubscriber implements SubscriberInterface
{
    /** @var LoggerInterface */
    private $logger;

    /** @var Formatter Formatter used to format log messages */
    private $formatter;

    /**
     * @param LoggerInterface|callable|resource|null $logger Logger used to log
     *     messages. Pass a LoggerInterface to use a PSR-3 logger. Pass a
     *     callable to log messages to a function that accepts a string of
     *     data. Pass a resource returned from ``fopen()`` to log to an open
     *     resource. Pass null or leave empty to write log messages using
     *     ``echo()``.
     * @param string|Formatter $formatter Formatter used to format log
     *     messages or a string representing a message formatter template.
     */
    public function __construct($logger = null, $formatter = null)
    {
        $this->logger = $logger instanceof LoggerInterface
            ? $logger
            : new SimpleLogger($logger);

        $this->formatter = $formatter instanceof Formatter
            ? $formatter
            : new Formatter($formatter);
    }

    public function getEvents()
    {
        return [
            // Fire after responses are verified (which trigger error events).
            'complete' => ['onComplete', RequestEvents::VERIFY_RESPONSE - 10],
            'error'    => ['onError', RequestEvents::EARLY]
        ];
    }

    public function onComplete(CompleteEvent $event)
    {
        $this->logger->log(
            substr($event->getResponse()->getStatusCode(), 0, 1) == '2'
                ? LogLevel::INFO
                : LogLevel::WARNING,
            $this->formatter->format(
                $event->getRequest(),
                $event->getResponse()
            ), [
                'request' => $event->getRequest(),
                'response' => $event->getResponse()
            ]
        );
    }

    public function onError(ErrorEvent $event)
    {
        $ex = $event->getException();
        $this->logger->log(
            LogLevel::CRITICAL,
            $this->formatter->format(
                $event->getRequest(),
                $event->getResponse(),
                $ex
            ), [
                'request' => $event->getRequest(),
                'response' => $event->getResponse(),
                'exception' => $ex
            ]
        );
    }
}
