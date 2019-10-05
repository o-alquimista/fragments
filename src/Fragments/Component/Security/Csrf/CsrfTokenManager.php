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

namespace Fragments\Component\Security\Csrf;

use Fragments\Component\SessionManagement\Session;

class CsrfTokenManager
{
    private const PREFIX = '_csrf/';

    private $session;

    public function __construct()
    {
        $this->session = new Session;
    }

    public function getToken($id)
    {
        $tokenName = self::PREFIX . $id;

        if ($this->session->exists($tokenName)) {
            return $this->session->get($tokenName);
        }

        $value = $this->generate();
        $this->session->set($tokenName, $value);

        return $value;
    }

    public function isTokenValid($tokenReceived, $id)
    {
        $tokenName = self::PREFIX . $id;

        if (false === $this->session->exists($tokenName)) {
            return false;
        }

        $tokenStored = $this->session->get($tokenName);
        $tokenValid = hash_equals($tokenStored, $tokenReceived);

        if ($tokenValid) {
            $this->session->destroy($tokenName);

            return true;
        }

        return false;
    }

    private function generate()
    {
        /*
         * Generate an URI safe base64 encoded string that does not contain "+",
         * "/" or "=" which need to be URL encoded and make URLs
         * unnecessarily longer.
         */
        $string = random_bytes(256 / 8);

        $token = rtrim(strtr(base64_encode($string), '+/', '-_'), '=');

        return $token;
    }
}
