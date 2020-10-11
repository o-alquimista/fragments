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

namespace Fragments\Bundle\Controller;

use Fragments\Component\Http\Request;
use Fragments\Component\Http\Response;
use Fragments\Component\Http\RedirectResponse;
use Fragments\Component\CsrfTokenManager;
use Fragments\Component\Feedback;
use Fragments\Component\Templating;
use Fragments\Component\Routing\Router;

abstract class AbstractController
{
    /**
     * Conveniently render a template from the controllers.
     */
    protected function render(string $template, array $variables = []): Response
    {
        $templating = new Templating();

        return $templating->render($template, $variables);
    }

    protected function redirectToRoute(string $routeId, $parameters = []): RedirectResponse
    {
        $router = new Router();
        $url = $router->generateUrl($routeId, $parameters);

        return new RedirectResponse($url);
    }

    protected function isCsrfTokenValid(string $targetId, string $token): bool
    {
        $csrfTokenManager = new CsrfTokenManager;

        return $csrfTokenManager->isTokenValid($token, $targetId);
    }

    protected function addFeedback(string $id, string $message)
    {
        $feedback = new Feedback;
        $feedback->add($id, $message);
    }
}
