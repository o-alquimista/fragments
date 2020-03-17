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
use Fragments\Component\Security\Csrf\CsrfTokenManager;

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

    public function escape(string $value)
    {
        echo htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    public function getCsrfToken(string $id)
    {
        $csrfManager = new CsrfTokenManager;
        $token = $csrfManager->getToken($id);

        echo $token;
    }
}