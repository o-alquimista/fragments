<?php

namespace Fragments\Models\Profile;

use Fragments\Utility\Connection\DatabaseConnection;

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