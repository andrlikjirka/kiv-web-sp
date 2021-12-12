<?php

namespace kivweb_sp\models;

/**
 * Trida pro spravu registrace uzivatele
 * @author jandrlik
 */
class Registration
{
    /** @var Database $db Objekt pro praci s databazi  */
    private $db;

    /**
     * Konstruktor inicializuje tridu registrace, ziska objekt pro pripojeni k databazi
     */
    public function __construct()
    {
        $this->db = Database::getDBConnection();
    }

    /**
     * Funkce pro registraci noveho uzivatele
     * @param string $jmeno Jmeno uzivatele
     * @param string $prijmeni Prijmeni uzivatele
     * @param string $login Login uzivatele
     * @param string $hash_password Zasifrovane heslo uzivatele
     * @param string $email Email uzivatele
     * @param int $id_pravo ID prava uzivatele
     * @return bool Vrati true, pokud byla registrace uspesna, jinak false
     */
    public function registrateUser(string $jmeno, string $prijmeni, string $login, string $hash_password, string $email, int $id_pravo = 4)
    {
        return $this->db->addNewUser($jmeno, $prijmeni, $login, $hash_password, $email, $id_pravo);
    }


}