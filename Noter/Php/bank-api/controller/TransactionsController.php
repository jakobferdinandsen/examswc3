<?php
require_once 'controllerIncludes.php';
require_once './model/modelIncludes.php';

class TransactionsController {
    /**
     * @return Transaction[]
     */
    public static function selectAll() {
        $tbl_name = 'trasnsations';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM $tbl_name");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $arr = array();

        foreach ($result as $row) {
            $id = $row['id'];
            $user_offer_id = $row['user_offer_id'];
            $user_buy_id = $row['user_buy_id'];
            $amount = $row['amount'];
            $offertime = $row['offer_time'];
            $buytime = $row['buy_time'];

            $transaction = new Transaction($id, $user_offer_id, $user_buy_id, $amount, $offertime, $buytime);
            array_push($arr, $transaction);
        }
        return $arr;
    }

    /**
     * @return Transaction[]
     */
    public static function selectAllOffers() {
        $tbl_name = 'trasnsations';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM $tbl_name WHERE `user_buy_id` IS NULL ORDER BY `offer_time`");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $arr = array();

        foreach ($result as $row) {
            $id = $row['id'];
            $user_offer_id = $row['user_offer_id'];
            $user_buy_id = $row['user_buy_id'];
            $amount = $row['amount'];
            $offertime = $row['offer_time'];
            $buytime = $row['buy_time'];

            $transaction = new Transaction($id, $user_offer_id, $user_buy_id, $amount, $offertime, $buytime);
            array_push($arr, $transaction);
        }
        return $arr;
    }

    /**
     * @param $id
     * @return null|Transaction
     */
    public static function selectById($id) {
        $tbl_name = 'trasnsations';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM $tbl_name WHERE id=:id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();
        if ($result) {
            $row = $result[0];
            $id = $row['id'];
            $user_offer_id = $row['user_offer_id'];
            $user_buy_id = $row['user_buy_id'];
            $amount = $row['amount'];
            $offertime = $row['offer_time'];
            $buytime = $row['buy_time'];

            $obj = new Transaction($id, $user_offer_id, $user_buy_id, $amount, $offertime, $buytime);
            return $obj;
        } else {
            return null;
        }
    }

    /**
     * @param Transaction $transaction
     */
    public static function validateBuy($transaction, $userid) {
        try {
            $accOffer = AccountsController::selectByUserId($transaction->getUserOffer()->getId())[0];
            $accBuy = AccountsController::selectByUserId($userid)[0];

            $amount = $transaction->getAmount();
            $offerRate = ExtRateController::selectByUserId($transaction->getUserOffer()->getId());
            $buyRate = ExtRateController::selectByUserId($userid);

            $amountOffer = $amount;
            $amountBuy = ($buyRate->getRate() / $offerRate->getRate()) * $amount; //this is what the Buy gets from offer
            $amountBuyPris = ($offerRate->getRate() / $buyRate->getRate()) * $amount; //this is the price of the transaction

            $conn = DatabaseConnection::getConnection();
            $tbl_name = 'trasnsations';
            // begin the transaction
            $conn->beginTransaction();
            $id = $transaction->getId();
            AccountsController::update($conn, $accBuy, -$amountBuyPris);
            AccountsController::update($conn, $accBuy, $amountBuy);
            AccountsController::update($conn, $accOffer, $amountOffer);

            $sql = "UPDATE `$tbl_name` SET `user_buy_id`=:userid, `buy_time`=CURRENT_TIMESTAMP WHERE id=:id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':userid', $userid);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            // commit the transaction
            $conn->commit();
            return TransactionsController::selectById($transaction->getId());
        } catch (PDOException $e) {
            $conn->rollback();
            processBAD("500", "validate transaction", $sql . "<br>" . $e->getMessage());
        }

        $conn = null;

    }

    /**
     * @param Users $user
     * @param double $amount
     * @return null|Transaction
     */
    public static function validateSell($user, $amount) {
        try {
            $userId = $user->getId();
            $acc = AccountsController::selectByUserId($userId)[0];

            $conn = DatabaseConnection::getConnection();
            // begin the transaction
            $conn->beginTransaction();
            AccountsController::update($conn, $acc, -$amount);

            $tbl_name = 'trasnsations';

            $sql = "INSERT INTO `$tbl_name` (`id`, `user_offer_id`, `user_buy_id`, `amount`, `offer_time`, `buy_time`) VALUES (NULL, :userId, NULL, :amount, CURRENT_TIMESTAMP, NULL);";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':amount', $amount);
            $stmt->bindParam(':userId', $userId);
            $stmt->execute();
            $transactionId = $conn->lastInsertId();
            // commit the transaction
            $conn->commit();
            return TransactionsController::selectById($transactionId);
        } catch (PDOException $e) {
            $conn->rollback();
            processBAD("500", "validate transaction", $sql . "<br>" . $e->getMessage());
        }

        $conn = null;

    }
    //SELECT * FROM trasnsations WHERE buy_time > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)

    /**
     * @return Transaction[]
     */
    public static function selectLastHour() {
        $tbl_name = 'trasnsations';
        $conn = DatabaseConnection::getConnection();

        $stmt = $conn->prepare("SELECT * FROM $tbl_name WHERE buy_time > DATE_SUB(CURRENT_TIMESTAMP, INTERVAL 1 HOUR)");
        $stmt->execute();

        // set the resulting array to associative
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $result = $stmt->fetchAll();

        $arr = array();

        foreach ($result as $row) {
            $id = $row['id'];
            $user_offer_id = $row['user_offer_id'];
            $user_buy_id = $row['user_buy_id'];
            $amount = $row['amount'];
            $offertime = $row['offer_time'];
            $buytime = $row['buy_time'];

            $transaction = new Transaction($id, $user_offer_id, $user_buy_id, $amount, $offertime, $buytime);
            array_push($arr, $transaction);
        }
        return $arr;
    }
}