<?php

/**
* @author Constan van Suchtelen van de Haere <constan.vansuchtelenvandehaere@hostingbe.com>
* @copyright 2024 HostingBE
*
* Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation
* files (the “Software”), to deal in the Software without restriction, including without limitation the rights to use, copy,
* modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software
* is furnished to do so, subject to the following conditions:

* The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
* THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES
* OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS
* BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF
* OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
*
*/

namespace HostingBE\Api;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Exception\RequestException;
use Psr\Log\LoggerInterface;

abstract class coreAPI {

    protected $logger;
    protected $client;
    protected $useragent = 'Multiple options API HostingBE/1.0';
    protected $baseUri = 'https://sandbox-wbapi.thesslstore.com/rest';
 //   protected $baseUri = 'https://api.thesslstore.com/rest';
     const MAX_RETRIES = 3;


public function __construct(LoggerInterface $logger) {
        $this->logger = $logger;
        $this->client = new Client([
            'base_uri' => $this->baseUri,
            'handler' => $this->createHandlerStack(),
            'timeout'  => 20.0,
            'http_errors' => false,
            'headers' => [
                'Accept' => 'application/json',
                'User-Agent' => $this->useragent,
            ],
         'verify' => true
        ]);
    }

protected function createHandlerStack() :object {
    $stack = HandlerStack::create();
    $stack->push(Middleware::retry($this->retryDecider(), $this->retryDelay()));
    return $this->createLoggingHandlerStack($stack);
    }  

protected function createLoggingHandlerStack(HandlerStack $stack) :object {
        $messageFormats = [
            '{method} {uri} HTTP/{version}',
            'HEADERS: {req_headers}',
            'BODY: {req_body}',
            'RESPONSE: {code} - {res_body}',
        ];
        foreach ($messageFormats as $messageFormat) {
            $stack->unshift(
                $this->createGuzzleLoggingMiddleware($messageFormat)
            );
        }
    return $stack;
    }


protected function createGuzzleLoggingMiddleware(string $messageFormat) {
        return Middleware::log(
            $this->logger,
            new MessageFormatter($messageFormat)
        );
}

protected function retryDecider() {
    return function (
        $retries,
        Request $request,
        Response $response = null,
        RequestException $exception = null
    ) {
    if ($retries >= self::MAX_RETRIES) {
    	return false;
    }
    /**
    *  retry on on timeouts
    */
    if ($exception instanceof ConnectException) {
        $this->logger->info('Timeout encountered, retrying');
        return true;
    }
            if ($response) {
                /**
                 *  retry on server error
                 */
                if ($response->getStatusCode() >= 500) {
                    $this->logger->info('Server 5xx error encountered, retrying...');
                    return true;
                }
            }
            return false;
        };
    }


/**
* delay 1s 2s 3s 4s 5s ...
*
* @return callable
*/
protected function retryDelay() {
        return function ($numberOfRetries) {
            return 1000 * $numberOfRetries;
        };
    }
}
?>