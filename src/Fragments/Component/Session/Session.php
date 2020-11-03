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

namespace Fragments\Component\Session;

use Fragments\Component\Http\Request;

class Session
{
    public function start(bool $readAndClose = false): bool
    {
        $options = [
            'cookie_httponly' => 1,
            'use_strict_mode' => 1,
        ];

        if ($readAndClose) {
            $this->options['read_and_close'] = true;
        }

        if (version_compare(phpversion(), '7.3.0', '>')) {
            $this->options['cookie_samesite'] = 'Lax';
        }

        $request = new Request();

        if ($request->isSecure()) {
            $this->options['cookie_secure'] = 1;
        }

        return session_start($options);
    }

    public function regenerate(): bool
    {
        return session_regenerate_id();
    }
    
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    public function get($key, $default = null)
    {
        if (array_key_exists($key, $_SESSION)) {
            return $_SESSION[$key];
        }
        
        return $default;
    }
    
    public function exists($key): bool
    {
        if (array_key_exists($key, $_SESSION)) {
            return true;
        }
        
        return false;
    }
    
    public function delete($key)
    {
        if (array_key_exists($key, $_SESSION)) {
            unset($_SESSION[$key]);
        }
    }
    
    public function destroy()
    {
        $_SESSION = [];
    }
}