<?php

/**
 * Copyright 2019 Douglas Silva (0x9fd287d56ec107ac)
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

namespace Fragments\Utility\Feedback;

use Fragments\Utility\Feedback\AbstractFeedback;

class WarningFeedback extends AbstractFeedback
{
    protected $feedbackType = 'warning';

    protected $feedbackText = array(
        'FEEDBACK_USERNAME_EMPTY' => 'Username was left empty',
        'FEEDBACK_USERNAME_LENGTH' => 'Username must be longer than 3 and '.
            'shorter than 26 characters',
        'FEEDBACK_USERNAME_INVALID' => 'Username can only contain '.
            'alphanumerical characters(a-z, A-Z, 0-9) and underscore(_).',
        'FEEDBACK_PASSWORD_EMPTY' => 'Password was left empty',
        'FEEDBACK_PASSWORD_LENGTH' => 'Minimum password length is 8 characters',
        'FEEDBACK_NOT_REGISTERED' => 'Invalid credentials',
        'FEEDBACK_INCORRECT_PASSWD' => 'Invalid credentials',
        'FEEDBACK_USERNAME_TAKEN' => 'Username already taken',
    );
}
