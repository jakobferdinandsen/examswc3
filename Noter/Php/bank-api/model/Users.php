<?php


class Users {
    private $id;
    private $name;
    private $apikey;

    /**
     * Users constructor.
     * @param $id
     * @param $name
     * @param $apikey
     */
    public function __construct($id, $name, $apikey) {
        $this->id = $id;
        $this->name = strtoupper($name);
        $this->apikey = $apikey;
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
     * @return string
     */
    public function getName() {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getApikey() {
        return $this->apikey;
    }

    /**
     * @param mixed $apikey
     */
    public function setApikey($apikey) {
        $this->apikey = $apikey;
    }

    function __toString() {
        return 'name ' . $this->name . ' key:' . $this->apikey;
    }

    function toJSON() {
        return '{
        "name":"' . $this->name . '"
        }';
    }


}