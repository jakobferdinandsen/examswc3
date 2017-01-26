<?php
require_once "myLogger.php";
require_once "insert.php";

class DatabaseConnection {
    public static $masterKey = "***";
    private static $host = 'localhost';
    private static $username = 'bank';
    private static $password = '***';
//    private static $username = 'root';
//    private static $password = '';
    private static $db = 'bank_api';
    private static $tables = array("users", "accounts", "exchange_rates", "trasnsations");
    private static $usersFile = "./resource/allEmailKey.txt";

    /**
     * @return null|PDO
     */
    public static function getConnection() {
        $h = self::$host;
        $d = self::$db;
        try {
            $sqlcon = new PDO("mysql:host=$h;dbname=$d", self::$username, self::$password);
            $sqlcon->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $sqlcon;
        } catch
        (PDOException $e) {
            return null;
        }
    }

    public static function reset() {
        $conn = self::getConnection();
        $conn->beginTransaction();
        $stmtBuild = $conn->exec("SET FOREIGN_KEY_CHECKS = 0");
        foreach (self::$tables as $table) {
            $conn->exec("TRUNCATE TABLE $table");
        }
        $stmtBuild = $conn->exec("SET FOREIGN_KEY_CHECKS = 1");
        $conn->commit();
        insertStudentsWithKey(self::$usersFile);
    }

}
