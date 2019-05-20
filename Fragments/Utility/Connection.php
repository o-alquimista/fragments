<?php

/**
 *
 * Connection Utility
 *
 * Creates a database connection object.
 *
 */

namespace Fragments\Utility\Connection;

use PDOException;
use PDO;

interface Database {

    public function getConnection();

}

class DatabaseConnection implements Database {

    private $username = "alq";

    private $password = "alq";

    private $host = "localhost";

    private $database = "fragments";

    /**
     * Holds the database connection object
     * @var object $connection
     */

    private $connection;

    public function __construct() {

        try {

            $options = array(
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            );

            $this->connection = new PDO(
                "mysql:host=$this->host;dbname=$this->database",
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

    public function getConnection() {

        return $this->connection;

    }

}

?>