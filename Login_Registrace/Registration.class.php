<?php

/**
 * Trida pro spravu registrace uzivatele
 */
class Registration
{
    /** @var Database $db Objekt pro praci s databazi  */
    private $db;

    public function __construct()
    {
        require_once ("../Database/Database.class.php");
        $this->db = Database::getDBConnection();
    }


    public function registrateUser(string $jmeno, string $login, string $heslo, string $email, int $id_pravo = 4)
    {
        return $this->db->addNewUser($jmeno, $login, $heslo, $email, $id_pravo);
    }


}