<?php


class Transaction {
    private $id;
    private $user_offer;
    private $user_buy;
    private $amount;
    private $offertime;
    private $buytime;

    /**
     * Transaction constructor.
     * @param $id
     * @param $user_offer_id
     * @param $user_buy_id
     * @param $amount
     * @param $offertime
     * @param $buytime
     */
    public function __construct($id, $user_offer_id, $user_buy_id, $amount, $offertime, $buytime) {
        $this->id = $id;
        $this->user_offer = UserController::select($user_offer_id);

        if ($user_buy_id) {
            $this->user_buy = UserController::select($user_buy_id);
        } else {
            $this->user_buy = null;
        }

        $this->amount = $amount;
        $this->offertime = $offertime;
        $this->buytime = $buytime;
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
    public function getUserOffer() {
        return $this->user_offer;
    }

    /**
     * @param Users $user_offer
     */
    public function setUserOffer($user_offer) {
        $this->user_offer = $user_offer;
    }

    /**
     * @return Users
     */
    public function getUserBuy() {
        return $this->user_buy;
    }

    /**
     * @param Users $user_buy
     */
    public function setUserBuy($user_buy) {
        $this->user_buy = $user_buy;
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

    /**
     * @return mixed
     */
    public function getOffertime() {
        return $this->offertime;
    }

    /**
     * @param mixed $offertime
     */
    public function setOffertime($offertime) {
        $this->offertime = $offertime;
    }

    /**
     * @return mixed
     */
    public function getBuytime() {
        return $this->buytime;
    }

    /**
     * @param mixed $buytime
     */
    public function setBuytime($buytime) {
        $this->buytime = $buytime;
    }


    function __toString() {
        return '{
            "id":"' . $this->getId() . '",
            "offerCurrency":"' . $this->getUserOffer()->getName() . '", 
            "buyCurrency":"' . $this->getUserBuy()->getName() . '",
            "amount":"' . $this->getAmount() . '",
            "offerTime":"' . $this->getOffertime() . '",
            "buyTime":"' . $this->getBuytime() . '"
            }';
    }

    function toJSON() {
        return '{
            "id":"' . $this->getId() . '",
            "amount":"' . $this->getAmount() . '", 
            "currency":"' . $this->getUserOffer()->getName() . '",
            "since":"' . $this->getOffertime() . '"
            }';
    }


}