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

namespace Fragments\Component\Database;

use PDOException;
use PDO;

/**
 * Connection Utility
 *
 * Creates a database connection object.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class PDOConnection
{
    private $username;

    private $password;

    private $host;

    private $database;

    private $driver;

    /**
     * @var object database connection object (PDO)
     */
    private $connection;

    /**
     * Builds a PDO object.
     *
     * @throws PDOException
     */
    public function __construct()
    {
        $this->loadConfig();

        try {
            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            );

            $this->connection = new PDO(
                "$this->driver:host=$this->host;dbname=$this->database",
                $this->username, $this->password, $options
            );
        } catch(PDOException $err) {
            $userFeedback = 'Something went wrong. This event will be reported.';
            $technicalError = $err->getMessage() . ' at line ' . $err->getLine();

            error_log($technicalError);
            echo $userFeedback;

            exit;
        }
    }

    /**
     * Retrieves the PDO object.
     *
     * @return object
     */
    public function getConnection()
    {
        return $this->connection;
    }

    private function loadConfig()
    {
        $config = simplexml_load_file('../config/database.xml');

        $this->username = (string)$config->username;
        $this->password = (string)$config->password;
        $this->host = (string)$config->host;
        $this->database = (string)$config->name;
        $this->driver = (string)$config->driver;
    }
}
