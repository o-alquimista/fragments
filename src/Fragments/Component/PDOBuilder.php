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

class PDOBuilder
{
    public function getConnection(): \PDO
    {
        $config = $this->getConfig();

        try {
            // FIXME: is this needed?
            $options = array(
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION,
            );

            $pdo = new \PDO(
                $config['pdo_driver'] . ":host=" . $config['host'] . ";port=" . $config['port'] . ";dbname=" . $config['database_name'],
                $config['username'], $config['password'], $options
            );
        } catch(\PDOException $error) {
            // FIXME: throw server error exception
        }

        return $pdo;
    }

    private function getConfig(): array
    {
        $config = parse_ini_file('../config/database.ini');

        return $config;
    }
}
