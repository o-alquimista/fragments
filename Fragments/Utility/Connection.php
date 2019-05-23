<?php

namespace Fragments\Utility\Connection;

use PDOException;
use PDO;

/**
 * Connection Utility
 *
 * Creates a database connection object.
 *
 * @author Douglas Silva <0x9fd287d56ec107ac>
 */
class DatabaseConnection
{
    private $username = "alq";

    private $password = "alq";

    private $host = "localhost";

    private $database = "fragments";

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

    /**
     * Retrieves the PDO object.
     *
     * @return object
     */
    public function getConnection()
    {
        return $this->connection;
    }
}