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

use Fragments\Component\Request;
use Fragments\Component\CsrfTokenManager;
use Fragments\Component\Feedback;
use Fragments\Component\TemplateHelper;

abstract class AbstractController
{
    protected function renderTemplate(string $path, array $variables = []) {
        $templateHelper = new TemplateHelper;
        $templateHelper->render($path, $variables);
    }

    protected function isFormSubmitted(): bool
    {
        $request = new Request;

        if ($request->requestMethod() == "POST") {
            return true;
        }

        return false;
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
