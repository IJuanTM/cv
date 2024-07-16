<?php

namespace app\Database;

use app\Controllers\{LogController, PageController};
use PDO;
use PDOException;

/**
 * The Database class is used for connecting to the database and executing queries.
 */
class Database
{
    private PDO $pdo;
    private object $stmt;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME,
                DB_USERNAME,
                DB_PASSWORD,
                [
                    PDO::ATTR_PERSISTENT => true,
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch (PDOException $e) {
            // Log error
            LogController::log($e->getMessage(), 'database');

            // Redirect to error page
            PageController::redirect('error/500');
        }
    }

    /**
     * This method is for preparing a query for execution.
     *
     * @param $query
     *
     * @return void
     */
    public function query($query): void
    {
        $this->stmt = $this->pdo->prepare($query);
    }

    /**
     * This method is for binding a value to a parameter.
     *
     * @param $param
     * @param $value
     * @param null $type
     *
     * @return void
     */
    public function bind($param, $value, $type = null): void
    {
        $type = $type ?? match (true) {
            is_int($value) => PDO::PARAM_INT,
            is_bool($value) => PDO::PARAM_BOOL,
            $value === null => PDO::PARAM_NULL,
            default => PDO::PARAM_STR,
        };
        $this->stmt->bindValue($param, $value, $type);
    }

    /**
     * This method is for executing a prepared statement and returning the result set as an array.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        $this->execute();
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * This method is for executing a prepared statement.
     *
     * @return void
     */
    public function execute(): void
    {
        try {
            $this->stmt->execute();
        } catch (PDOException $e) {
            // Log error
            LogController::log($e->getMessage(), 'database');

            // Redirect to error page
            PageController::redirect('error/500');
        }
    }

    /**
     * This method is for executing a prepared statement and returning the first row of the result set.
     *
     * @return array
     */
    public function single(): array
    {
        $this->execute();
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * This method is for getting the number of rows affected by the last SQL statement.
     *
     * @return int
     */
    public function rowCount(): int
    {
        $this->execute();
        return $this->stmt->rowCount();
    }
}
