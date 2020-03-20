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

use Fragments\Component\SessionManagement\Session;
use Fragments\Bundle\Exception\AccessDeniedHttpException;

/**
 * Manage tokens used to prevent CSRF attacks.
 */
class CsrfTokenManager
{
    private const PREFIX = '_csrf/';

    private $session;

    public function __construct()
    {
        $this->session = new Session;
    }

    /**
     * Get a new CSRF token.
     *
     * If the token ID already exists, it returns its value from the session.
     */
    public function getToken(string $id): string
    {
        $tokenName = self::PREFIX . $id;

        if ($this->session->exists($tokenName)) {
            return $this->session->get($tokenName);
        }

        $value = $this->generate();
        $this->session->set($tokenName, $value);

        return $value;
    }

    /**
     * Check validity of a CSRF token.
     */
    public function isTokenValid(string $tokenReceived, string $targetId): bool
    {
        $targetId = self::PREFIX . $targetId;

        if (false === $this->session->exists($targetId)) {
            throw new AccessDeniedHttpException('The CSRF token identifier could not be found.');
        }

        $tokenStored = $this->session->get($targetId);
        $tokenValid = hash_equals($tokenStored, $tokenReceived);

        if ($tokenValid) {
            $this->session->destroy($targetId);

            return true;
        }

        return false;
    }

    /**
     * Generate an URI safe base64 encoded string that does not contain "+",
     * "/" or "=" which need to be URL encoded and make URLs unnecessarily
     * longer.
     */
    private function generate(): string
    {
        $string = random_bytes(256 / 8);

        $token = rtrim(strtr(base64_encode($string), '+/', '-_'), '=');

        return $token;
    }
}
