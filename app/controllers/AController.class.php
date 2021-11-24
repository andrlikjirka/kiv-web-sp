<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;
use kivweb_sp\models\Registration;

abstract class AController implements IController
{

    /** @var Database $db Objekt pripojeni k databazi */
    protected $db;

    protected $login;

    protected $registration;

    public function __construct()
    {
        $this->db = Database::getDBConnection();
        $this->login = new Login();
        $this->registration = new Registration();
    }

    protected function handleLoginForm()
    {
        // zpracovani odeslanych formularu
        if (isset($_POST['action'])) {
            //prihlaseni
            if ($_POST['action'] == 'login' && isset($_POST['login']) && isset($_POST['heslo'])) {
                //pokusim se prihlasit uzivatele
                $result = $this->login->login($_POST['login'], $_POST['heslo']);
                if ($result) {
                    echo "OK: Uživatel byl přihlášen.";
                } else {
                    //echo "ERROR: Přihlášení uživatele se nezdařilo";
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Nesprávné jméno nebo heslo.</div>";
                }
            } //odhlaseni
            else if ($_POST['action'] == 'logout') {
                //odhlasim uzivatele
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
            } //neznama akce
            else {
                echo "WARNING: Neznama akce.";
            }
        }
    }

    protected function handleRegistrationForm()
    {
        // zpracovani odeslaneho registracniho formulare
        if (isset($_POST['registrace'])) {
            //mam vsechny pozadovane hodnoty?
            if (isset($_POST['login']) && isset($_POST['password1']) && isset($_POST['password2'])
                && isset($_POST['jmeno']) && isset($_POST['prijmeni']) && isset($_POST['email'])
                && $_POST['password1'] == $_POST['password2']
                && $_POST['login'] != "" && $_POST['password1'] != "" && $_POST['jmeno'] != "" && $_POST['prijmeni'] != "" && $_POST['email'] != ""
            ) {
                $hash_password = password_hash($_POST['password1'], PASSWORD_BCRYPT);
                $result = $this->registration->registrateUser($_POST['jmeno'], $_POST['prijmeni'], $_POST['login'], $hash_password, $_POST['email']);
                if ($result) {
                    //echo "OK: Uživatel byl přidán do databáze.";
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Registrace uživatele proběhla úspěšně.</div>";
                } else {
                    //echo "ERROR: Uložení uživatele do databáze se nezdařilo";
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Registrace uživatele se nezdařila.</div>";
                }
            } else {
                //nemam vsechny atributy
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Registrace uživatele se nezdařila.</div>";
            }
        }
    }

    /**
     * Funkce vrati data uvodni stranky (implementovano v potomcich - kotrollerech)
     * @param string $pageTitle Nazev stranky
     * @return array Data predana sablone
     */
    abstract public function show(string $pageTitle): array;

}