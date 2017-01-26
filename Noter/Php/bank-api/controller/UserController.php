<?php
require_once 'controllerIncludes.php';
require_once './model/modelIncludes.php';

//require_once 'SuperController.php';
class UserController {

    /**
     * @return Users[]
     */
    public static function selectAll() {
        $tbl_name = 'users';
        $conn = DatabaseConnection::getConnection();
        $stmt = $conn->prepare("SELECT * FROM $tbl_name");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $arr = array();

        foreach ($result as $row) {
            $id = $row['id'];
            $name = $row['name'];
            $apikey = $row['apikey'];

            $obj = new Users($id, $name, $apikey);
            array_push($arr, $obj);
        }
        return $arr;
    }


    public static function select($id) {
        $tbl_name = 'users';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM $tbl_name WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $row = $result[0];
        $id = $row['id'];
        $name = $row['name'];
        $apikey = $row['apikey'];

        $user = new Users($id, $name, $apikey);

        return $user;
    }

    public static function selectByKey($key) {
        $tbl_name = 'users';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM `$tbl_name` WHERE `apikey`=:key");
        $stmt->bindParam(':key', $key);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if ($result) {
            $row = $result[0];
            $id = $row['id'];
            $name = $row['name'];
            $apikey = $row['apikey'];

            $user = new Users($id, $name, $apikey);

            return $user;
        } else {
            return null;
        }

    }

    public static function selectByName($userName) {
        $tbl_name = 'users';
        $userName = strtolower($userName);
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM `$tbl_name` WHERE `name`=:userName");
        $stmt->bindParam(':userName', $userName);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if ($result) {
            $row = $result[0];
            $id = $row['id'];
            $name = $row['name'];
            $apikey = $row['apikey'];

            $user = new Users($id, $name, $apikey);

            return $user;
        } else {
            return null;
        }

    }

    public static function insert($userName, $keyArg = null) {
        try {

            $conn = DatabaseConnection::getConnection();
            // begin the transaction
            $conn->beginTransaction();

            $key = ($keyArg != null) ? $keyArg : md5(microtime() . rand());

            $sql = "INSERT INTO `users` (`id`, `name`, `apikey`) VALUES (NULL, :userName, :key)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userName', $userName);
            $stmt->bindParam(':key', $key);
            $stmt->execute();
            $lastId = $conn->lastInsertId();
            // commit the transaction
            $conn->commit();
            return UserController::select($lastId);
        } catch (PDOException $e) {
            $conn->rollback();
            processBAD("500", "inser user", $sql . "<br>" . $e->getMessage());
        }

        $conn = null;


    }

}