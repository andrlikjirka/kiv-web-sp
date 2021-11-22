<?php

namespace kivweb_sp\models;

/**
 * Trida pro spravu registrace uzivatele
 */
class Registration
{
    /** @var Database $db Objekt pro praci s databazi  */
    private $db;

    public function __construct()
    {
        //require_once("../database/Database.class.php");
        $this->db = Database::getDBConnection();
    }

    public function registrateUser(string $jmeno, string $login, string $heslo, string $email, int $id_pravo = 4)
    {
        $hash_password = password_hash($heslo, PASSWORD_BCRYPT);
        return $this->db->addNewUser($jmeno, $login, $hash_password, $email, $id_pravo);
    }


}