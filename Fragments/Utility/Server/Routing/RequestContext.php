<?php

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