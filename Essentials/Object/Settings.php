<?php
class Settings extends DBObject
{
    public $token = "";
    public function __construct($token) {
        parent::__construct('settings');
        $q = "SELECT *, (SELECT name FROM user_info WHERE user_info.id = id_user_info) AS name, (SELECT surname FROM user_info WHERE user_info.id = id_user_info) AS surname FROM users WHERE id = (SELECT id_user FROM sessions_users WHERE token = '$token')";
        $r = mysqli_query(DataBase::$mysqli,$q);
        $this->nome = DataBase::getResult($r,"name");
        $this->cognome = DataBase::getResult($r,"surname");
        $this->denominazione = DataBase::getResult($r,"name").' '.DataBase::getResult($r,"surname");
    }
}