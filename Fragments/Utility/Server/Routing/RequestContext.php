<?php

/**
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

namespace Fragments\Utility\Server\Routing\RequestContext;

use Fragments\Utility\Server\Requests\ServerRequest;

/**
 * Request context.
 *
 * Stores information about the HTTP request.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class RequestContext
{
    public $uri;

    public $requestMethod;

    public function __construct()
    {
        $uri = ServerRequest::getURI();
        $uri = trim($uri, '/');
        $this->uri = $uri;

        $this->requestMethod = ServerRequest::requestMethod();
    }
}
