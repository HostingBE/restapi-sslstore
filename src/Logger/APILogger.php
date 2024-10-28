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

namespace HostingBE\Api\Logger;

use DateTime;
use DateTimeZone;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

class APILogger {

    public function create(string $channel = 'testing', string $fileNameHandle = 'app_log', string $location = __DIR__ . "/../../logs") {
        $logger = new Logger($channel);
        $dateFormat = "n/j/Y, g:i a";
        $formatter = new LineFormatter(null, $dateFormat, false, true);
        $now = (new DateTime("now"))->format('m_d_Y');
        $handler = new StreamHandler("{$location}/{$fileNameHandle}_{$now}.log", Logger::INFO);
        $handler->setFormatter($formatter);
        $logger->pushHandler($handler);
        $logger->setTimezone(new DateTimeZone('Europe/Amsterdam'));
        return $logger;
        }
}

?>