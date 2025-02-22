<?php

/**
 * Database Connection Class
 * Handles database connection and configuration
 */
class Database {
    // Database configuration constants
    private const DB_HOST = "localhost";
    private const DB_USER = "root";
    private const DB_PASS = "";
    private const DB_NAME = "ppdb_sd";
    private const DB_CHARSET = "utf8mb4";

    /** @var PDO */
    protected $conn;

    /**
     * Initialize database connection
     * 
     * @throws DatabaseException If connection fails
     */
    public function __construct() {
        try {
            $dsn = sprintf(
                "mysql:host=%s;dbname=%s;charset=%s",
                self::DB_HOST,
                self::DB_NAME,
                self::DB_CHARSET
            );

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES " . self::DB_CHARSET,
                PDO::ATTR_PERSISTENT => true
            ];

            $this->conn = new PDO($dsn, self::DB_USER, self::DB_PASS, $options);
            
        } catch(PDOException $e) {
            $this->logError('Connection failed', $e);
            throw new DatabaseException('Database connection failed');
        }
    }

    /**
     * Get database connection
     * 
     * @return PDO Active database connection
     * @throws DatabaseException If no connection exists
     */
    public function getConnection(): PDO {
        if (!$this->conn instanceof PDO) {
            throw new DatabaseException('No database connection exists');
        }
        return $this->conn;
    }

    /**
     * Begin a transaction
     * 
     * @return bool Success status
     */
    public function beginTransaction(): bool {
        return $this->conn->beginTransaction();
    }

    /**
     * Commit a transaction
     * 
     * @return bool Success status
     */
    public function commit(): bool {
        return $this->conn->commit();
    }

    /**
     * Rollback a transaction
     * 
     * @return bool Success status
     */
    public function rollback(): bool {
        return $this->conn->rollBack();
    }

    /**
     * Close the database connection
     */
    public function closeConnection(): void {
        $this->conn = null;
    }

    /**
     * Log database errors
     * 
     * @param string $message Error message
     * @param Exception $e Exception object
     */
    private function logError(string $message, Exception $e): void {
        $timestamp = date('Y-m-d H:i:s');
        $errorMessage = sprintf(
            "[%s] Database Error: %s - %s" . PHP_EOL,
            $timestamp,
            $message,
            $e->getMessage()
        );
        error_log($errorMessage, 3, '../logs/database.log');
    }

    /**
     * Prevent cloning of the instance
     */
    private function __clone() {}

    /**
     * Prevent unserializing of the instance
     */
    public function __wakeup() {
        throw new DatabaseException('Cannot unserialize database connection');
    }

    /**
     * Clean up on destruction
     */
    public function __destruct() {
        $this->closeConnection();
    }
}

/**
 * Custom exception for database errors
 */
class DatabaseException extends Exception {}