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

namespace Fragments\Models\Profile;

use Fragments\Utility\Connection\DatabaseConnection;
use PDO;

/**
 * Profile service.
 *
 * Retrieves resources to populate the profile page.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class ProfileService
{
    public $username;

    public function getUserData($username)
    {
        $user = new User($username);

        if ($user->isRegistered() === false) {
            return false;
        }

        $user->getData($username);
        $this->username = $user->username;

        return true;
    }

    /**
     * Returns an array containing all registered usernames.
     *
     * @return array
     */
    public function getUserList()
    {
        $registry = new Registry;
        $list = $registry->retrieveUserList();

        return $list;
    }
}

/**
 * Data mapper
 *
 * Creates resources used by mappers
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
abstract class DataMapper
{
    /**
     * @var object database connection object (PDO)
     */
    protected $connection;

    public function __construct() {
        $connection = new DatabaseConnection;
        $this->connection = $connection->getConnection();
    }
}

/**
 * User operations
 *
 * Any user related task that doesn't fit anywhere else
 * should be implemented here.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
 class User
 {
     public $username;

     private $storage;

     public function __construct($username)
     {
         $this->storage = new UserMapper;
         $this->username = $username;
     }

     public function isRegistered()
     {
         $matchingRows = $this->storage->retrieveCount($this->username);

         if ($matchingRows == 0) {
             return false;
         }

         return true;
     }

     public function getData()
     {
         $data = $this->storage->retrieveData($this->username);
         $this->username = $data->username;
     }
 }

class UserMapper extends DataMapper
{
    /**
     * @param string $username
     * @return string
     */
    public function retrieveCount($username)
    {
        $query = "SELECT COUNT(id) FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        return $stmt->fetchColumn();
    }

    /**
     * @param string $username
     * @return object
     */
    public function retrieveData($username)
    {
        $query = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->connection->prepare($query);

        $stmt->bindParam(":username", $username);

        $stmt->execute();

        return $stmt->fetchObject();
    }
}

/**
 * Registry operations.
 *
 * Operations which do not concern a specific user.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class Registry
{
    private $storage;

    public function __construct()
    {
        $this->storage = new RegistryMapper;
    }

    /**
     * Retrieve all registered usernames.
     */
    public function retrieveUserList()
    {
        $list = $this->storage->getAllUsernames();

        return $list;
    }
}

class RegistryMapper extends DataMapper
{
    /**
     * @return array
     */
    public function getAllUsernames()
    {
        $query = "SELECT username FROM users";

        $stmt = $this->connection->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }
}
