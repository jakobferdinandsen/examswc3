<?php
require_once './controller/UserController.php';

class ExtRate {
    private $id;
    private $user;
    private $rate;


    public function __construct($id, $userid, $rate) {
        $this->id = $id;
        $this->user = UserController::select($userid);
        $this->rate = $rate;

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
    public function getRate() {
        return $this->rate;
    }

    /**
     * @param mixed $rate
     */
    public function setRate($rate) {
        $this->rate = $rate;
    }




    function __toString() {
        return $this->userid . '(' . $this->rate . ')';
    }

    function toJSON() {
        return '{
            "id":"' . $this->getId() . '",
            "amount":"' . $this->getRate() . '", 
            "currency":"' . $this->getUser()->getName() . '"
            }';
    }


}