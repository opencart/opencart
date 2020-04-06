=====================
Guzzle Log Subscriber
=====================

The LogSubscriber logs HTTP requests and responses to a
`PSR-3 logger <https://github.com/php-fig/log>`_, callable, resource returned
by ``fopen()``, or by calling ``echo()``.

Here's the simplest example of how it's used:

.. code-block:: php

    use GuzzleHttp\Client;
    use GuzzleHttp\Subscriber\Log\LogSubscriber;

    $client = new Client();
    $client->getEmitter()->attach(new LogSubscriber());
    $client->get('http://httpbin.org');

Running the above example will echo a message using the
`Apache Common Log Format (CLF) <http://httpd.apache.org/docs/1.3/logs.html#common>`_.

::

    [info] hostname Guzzle/5.0 curl/7.21.4 PHP/5.5.7 - [2014-03-01T22:48:13+00:00] "GET / HTTP/1.1" 200 7641

.. note::

    Because no logger is provided, the subscriber simply logs messages with
    ``echo()``. This is the method used for logging if ``null`` is provided.

Installing
----------

This project can be installed using Composer. Add the following to your
composer.json:

.. code-block:: javascript

    {
        "require": {
            "guzzlehttp/log-subscriber": "~1.0"
        }
    }

Using PSR-3 Loggers
-------------------

You can provide a PSR-3 logger to the constructor as well. The following
example shows how the LogSubscriber can be combined with
`Monolog <https://github.com/Seldaek/monolog>`_.

.. code-block:: php

    use GuzzleHttp\Client;
    use GuzzleHttp\Subscriber\Log\LogSubscriber;
    use Monolog\Logger;
    use Monolog\Handler\StreamHandler;

    // create a log channel
    $log = new Logger('name');
    $log->pushHandler(new StreamHandler('/path/to/your.log', Logger::WARNING));

    $client = new Client();
    $subscriber = new LogSubscriber($log);
    $client->getEmitter()->attach($subscriber);

Logging with a custom message format
------------------------------------

The LogSubscriber's constructor accepts a logger as the first argument and a
message format string or a message formatter as the second argument. You could
log the full HTTP request and Response message using the debug format via
``GuzzleHttp\Subscriber\Log\Formatter::DEBUG``.

.. code-block:: php

    use GuzzleHttp\Subscriber\Log\LogSubscriber;
    use GuzzleHttp\Subscriber\Log\Formatter;

    // Log the full request and response messages using echo() calls.
    $subscriber = new LogSubscriber(null, Formatter::DEBUG);

Message Formatter
~~~~~~~~~~~~~~~~~

Included in this repository is a *message formatter*. The message formatter is
used to format log messages for both requests and responses using a log
template that uses variable substitution for string enclosed in braces
(``{}``).

The following variables are available in message formatter templates:

{request}
    Full HTTP request message

{response}
    Full HTTP response message

{ts}
    Timestamp

{host}
    Host of the request

{method}
    Method of the request

{url}
    URL of the request

{protocol}
    Request protocol

{version}
    Protocol version

{resource}
    Resource of the request (path + query + fragment)

{hostname}
    Hostname of the machine that sent the request

{code}
    Status code of the response (if available)

{phrase}
    Reason phrase of the response  (if available)

{error}
    Any error messages (if available)

{req_header_*}
    Replace ``*`` with the lowercased name of a request header to add to the
    message.

{res_header_*}
    Replace ``*`` with the lowercased name of a response header to add to the
    message

{req_headers}
    Request headers as a string.

{res_headers}
    Response headers as a string.

{req_body}
    Request body as a string.

{res_body}
    Response body as a string.
