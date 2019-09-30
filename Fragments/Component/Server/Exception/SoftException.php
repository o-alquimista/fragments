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

namespace Fragments\Component\Server\Exception;

use Exception;
use Fragments\Component\Feedback;

/**
 * Soft Exceptions
 *
 * They are used for events that are not critical to the successful
 * execution of the program. In some cases, the program will fallback
 * to a predefined value.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class SoftException extends Exception
{
    public function sessionExpired()
    {
        Feedback::add(
            'danger',
            'This session has expired'
        );
    }
}
