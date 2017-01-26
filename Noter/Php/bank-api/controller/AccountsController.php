<?php
require_once 'controllerIncludes.php';
require_once './model/modelIncludes.php';

class AccountsController {

    /**
     * @param $userId
     * @return Accounts[]
     */
    public static function selectByUserId($userId) {
        $tbl_name = 'accounts';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM `$tbl_name` WHERE `user_id`=:userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $arr = array();

        foreach ($result as $row) {
            $id = $row['id'];
            $userid = $row['user_id'];
            $amout = $row['amout'];

            $obj = new Accounts($id, $userid, $amout);
            array_push($arr, $obj);
        }
        return $arr;
    }

    /**
     * @param PDO $conn
     * @param Accounts $account
     * @param double $amount
     * @return mixed
     */
    public static function update($conn, $account, $amount) {
        try {
            $amountAvailable = $account->getAmount();
            $newAmount = $amountAvailable + $amount;
            if ($newAmount <= 0) {
                $currency = $account->getUser()->getName();
                $amount *= (-1);
                processBAD("403", "update account", "You are poor. This transaction requires $amount $currency, but you have $amountAvailable $currency");
            }

            $tbl_name = 'accounts';
            $id = $account->getId();

            $sql = "UPDATE `$tbl_name` SET `amout`=:amount WHERE id=:id";

            // Prepare statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':amount', $newAmount);
            $stmt->bindParam(':id', $id);
            $stmt->execute();

            return $stmt->rowCount();
        } catch (PDOException $e) {
            $conn->rollBack();
            processBAD("500", "update account", $sql . "<br>" . $e->getMessage());
        }

        $conn = null;

    }

}