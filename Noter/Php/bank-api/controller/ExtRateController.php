<?php
require_once 'controllerIncludes.php';
require_once './model/modelIncludes.php';

class ExtRateController {

    public static function selectAll() {
        $tbl_name = 'exchange_rates';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM :tablename");
        $stmt->bindParam(':tablename', $tbl_name);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $arr = array();

        foreach ($result as $row) {
            $id = $row['id'];
            $userid = $row['user_id'];
            $rate = $row['rate'];

            $obj = new ExtRate($id, $userid, $rate);
            array_push($arr, $obj);
        }

        var_dump($arr);
        return $arr;
    }

    public static function selectByUserId($userId) {
        $tbl_name = 'exchange_rates';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM `$tbl_name` WHERE `user_id`=:userId");
        $stmt->bindParam(':userId', $userId);
        $stmt->execute();
        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        if ($result) {
            $row = $result[0];
            $id = $row['id'];
            $userid = $row['user_id'];
            $rate = $row['rate'];

            $obj = new ExtRate($id, $userid, $rate);

            return $obj;
        } else {
            return null;
        }
    }

    //TODO somthig is not working here
    /**
     * @return true if rate is !empty
     * @return false if rate is empty
     */
    public static function validateRate($user) {
        $conn = DatabaseConnection::getConnection();
        $conn->beginTransaction();
        $stmt = $conn->prepare("SELECT rate FROM exchange_rates WHERE `user_id`=:userId");
        $stmt->bindParam(':userId', $user);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $res = $stmt->fetchAll();
        $conn->commit();
        if (empty($res)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * @return ExtRate[]
     */
    public static function doChange() {
        $tArray = TransactionsController::selectLastHour();
        $arr = array();
        foreach ($tArray as $transaction) {
            $rateInc = $transaction->getAmount() / 1000;
            $rateOld = ExtRateController::selectByUserId($transaction->getUserOffer()->getId())->getRate();
            $rateNew = $rateOld + $rateInc;
            ExtRateController::update($transaction->getUserOffer()->getId(), $rateNew);

            array_push($arr, ExtRateController::selectByUserId($transaction->getUserOffer()->getId()));
        }

        return $arr;
    }

    public static function update($userId, $newRate) {
        try {

            $tbl_name = 'exchange_rates';

            $sql = "UPDATE `$tbl_name` SET `rate`=:rate WHERE user_id=:userid";

            $conn = DatabaseConnection::getConnection();
            $conn->beginTransaction();
            // Prepare statement
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':rate', $newRate);
            $stmt->bindParam(':userid', $userId);

            $stmt->execute();
            $conn->commit();

            return $stmt->rowCount();
        } catch (PDOException $e) {
            $conn->rollBack();
            processBAD("500", "update account", $sql . "<br>" . $e->getMessage());
        }

        $conn = null;

    }


}