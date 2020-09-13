<?php

/**
 * Copyright 2019-2020 Douglas Silva (0x9fd287d56ec107ac)
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

namespace Fragments\Component;

use Fragments\Component\Feedback;
use Fragments\Component\CsrfTokenManager;
use Fragments\Component\Request;
use Fragments\Component\SessionManagement\Session;
use Fragments\Component\Routing\Router;

class TemplateHelper {
    public function render(string $path, array $variables = [])
    {
        // Expose variables in the scope of the template
        foreach ($variables as $name => $value) {
            $$name = $value;
        }

        require($path);
    }

    public function getFeedback(): array
    {
        $feedback = new Feedback;

        return $feedback->get();
    }

    public function escape(string $value): string
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getCsrfToken(string $id): string
    {
        $csrfManager = new CsrfTokenManager;
        $token = $csrfManager->getToken($id);

        return $token;
    }

    public function getSession(): Session
    {
        return new Session;
    }

    public function getRequest(): Request
    {
        return new Request;
    }

    public function isCurrentPage(string $path): bool
    {
        $request = new Request;
        $uri = $request->getURI();

        if ($path == $uri) {
            return true;
        }

        return false;
    }
    
    public function generateUrl(string $routeId, array $parameters = []): string
    {
        $router = new Router();
        
        return $router->generateUrl($routeId, $parameters);
    }
    
    public function isAuthenticated(): bool
    {
        $session = new Session();
        
        if ($session->exists('user')) {
            return true;
        }
        
        return false;
    }
}