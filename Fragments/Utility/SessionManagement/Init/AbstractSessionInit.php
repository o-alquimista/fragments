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

namespace Fragments\Utility\SessionManagement\Init;

/**
 * Session initialization
 *
 * Important: This is only meant to be used within the
 * Session Utility. To start a new session at the
 * controllers, refer to the Session class in this file.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
abstract class AbstractSessionInit
{
    protected $options = array(
        'use_only_cookies' => 1,
        'use_trans_sid' => 0,
        'cookie_httponly' => 1,

        /*
         * 'cookie_samesite' => 1
         *
         * The 'samesite' option support starts
         * with PHP 7.3
         */

        /*
         * 'session.cookie_secure' => 1
         *
         * The 'cookie_secure' option can only
         * be enabled when SSL is configured
         */
    );
}
