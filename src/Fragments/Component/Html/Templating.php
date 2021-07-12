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

namespace Fragments\Component\Html;

use Fragments\Component\Http\Response;
use Fragments\Component\Http\Request;
use Fragments\Component\Routing\Router;
use Fragments\Component\Session\Session;
use Fragments\Component\Session\Feedback;
use Fragments\Component\Session\Csrf;

class Templating
{
    /**
     * Constructs a Response object with the output of a template file.
     */
    public function render(string $template, array $variables = []): Response
    {
        if (false === file_exists("../templates/{$template}")) {
            throw new \RuntimeException('The template file could not be found.');
        }

        ob_start();

        $context = $this->buildContext();
        extract($context, EXTR_PREFIX_ALL, 'app');
        extract($variables);

        include "../templates/{$template}";

        $contents = ob_get_contents();
        ob_end_clean();

        return new Response($contents);
    }
    
    /**
     * Includes a template file and returns its contents as a string.
     * 
     * @param string $template The template file relative to the '/templates' directory
     * @param array $variables Variables to include in the template
     * @throws \RuntimeException When the template file cannot be found
     * @return string The contents of the parsed template file
     */
    public function include(string $template, array $variables = []): string
    {
        if (false === file_exists("../templates/{$template}")) {
            throw new \RuntimeException('The template file could not be found.');
        }
        
        ob_start();

        $context = $this->buildContext();
        extract($context, EXTR_PREFIX_ALL, 'app');
        extract($variables);

        include "../templates/{$template}";

        $contents = ob_get_contents();
        ob_end_clean();
        
        return $contents;
    }

    /**
     * Returns an array of variables that can be useful when extracted into the
     * template's scope.
     */
    private function buildContext(): array
    {
        $context = [];

        $context['router'] = new Router();
        $context['feedback'] = new Feedback();
        $context['session'] = new Session();
        $context['request'] = new Request();

        return $context;
    }

    public function escape(string $string): string
    {
        return htmlspecialchars($string, flags: ENT_QUOTES);
    }

    public function getCsrfToken(string $name): string
    {
        $csrfManager = new Csrf();

        return $csrfManager->get($name);
    }
}
