<?php

/**
 * Copyright 2019-2021 Douglas Silva (0x9fd287d56ec107ac)
 *
 * This file is part of Fragments.
 *
 * Fragments is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with Fragments.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Fragments\Component\Http;

/**
 * An object-oriented representation of the HTTP response.
 */
class Response
{
    public string $content;

    public int $statusCode;

    public array $headers;

    public function __construct(string $content = '', int $statusCode = 200, array $headers = [])
    {
        $this->content = $content;
        $this->statusCode = $statusCode;
        $this->headers = $headers;
    }

    public function send()
    {
        $this->sendHeaders();
        $this->sendContent();
    }

    private function sendHeaders()
    {
        foreach ($this->headers as $name => $value) {
            header("{$name}: {$value}", true, $this->statusCode);
        }

        http_response_code($this->statusCode);
    }

    private function sendContent()
    {
        echo $this->content;
    }
}