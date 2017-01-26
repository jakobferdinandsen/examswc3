<?php
require_once './controller/controllerIncludes.php';


class Accounts {
    private $id;
    private $user;
    private $amount;

    /**
     * Accounts constructor.
     * @param $id
     * @param $userid
     * @param $amount
     */
    public function __construct($id, $userid, $amount) {
        $this->id = $id;
        $this->user = UserController::select($userid);
        $this->amount = $amount;
    }

    /**
     * @return mixed
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id) {
        $this->id = $id;
    }

    /**
     * @return Users
     */
    public function getUser() {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser($user) {
        $this->user = $user;
    }

    /**
     * @return mixed
     */
    public function getAmount() {
        return $this->amount;
    }

    /**
     * @param mixed $amount
     */
    public function setAmount($amount) {
        $this->amount = $amount;
    }

    function __toString() {
        return $this->toJSON();
    }


    function toJSON() {
        return '{
        "amount":"' . $this->getAmount() . '", 
        "currency":"' . $this->user->getName() . '"
        }';
    }


}