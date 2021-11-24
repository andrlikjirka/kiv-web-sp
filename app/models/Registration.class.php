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
        $this->db = Database::getDBConnection();
    }

    public function registrateUser(string $jmeno, string $prijmeni, string $login, string $hash_password, string $email, int $id_pravo = 4)
    {
        return $this->db->addNewUser($jmeno, $prijmeni, $login, $hash_password, $email, $id_pravo);
    }


}