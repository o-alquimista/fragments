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

use Fragments\Bundle\Exception\ServerErrorHttpException;

/**
 * Using the Singleton pattern, it creates an instance of the PDO class
 * and always returns that same instance.
 */
class PdoConnection
{
    private static $connection;

    public function getConnection(): \PDO
    {
        if (!self::$connection) {
            $config = parse_ini_file('../config/pdo.ini');
            
            if (!$config) {
                throw new ServerErrorHttpException('Failed to get connection parameters. Did you create the database.ini file at /config?');
            }
            
            // Optional parameters
            $charset = isset($config['charset']) ? ";charset={$config['charset']}" : '';
            $port = isset($config['port']) ? ";port={$config['port']}" : '';
            
            if (isset($config['socket'])) {
                $dsn = "{$config['driver']}:unix_socket={$config['socket']};dbname={$config['database']};{$charset}";
            } else {
                $dsn = "{$config['driver']}:host={$config['host']}{$port};dbname={$config['database']}{$charset}";
            }
            
            $options = [
                \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
            ];
            
            try {
                self::$connection = new \PDO($dsn, $config['username'], $config['password'], $options);
            } catch (\PDOException $e) {
                error_log($e);
                
                throw new ServerErrorHttpException('Failed to connect to the database.');
            }
        }
        
        return self::$connection;
    }
}
