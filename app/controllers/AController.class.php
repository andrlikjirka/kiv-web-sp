<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

abstract class AController implements IController
{

    /** @var Database $db Objekt pripojeni k databazi */
    protected $db;

    protected $login;

    public function __construct()
    {
        $this->db = Database::getDBConnection();
        $this->login = new Login();
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
                    echo "ERROR: Přihlášení uživatele se nezdařilo";
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Nesprávné jméno nebo heslo.</div>";
                }
            }
            //odhlaseni
            else if ($_POST['action'] == 'logout') {
                //odhlasim uzivatele
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
            }
            //neznama akce
            else {
                echo "WARNING: Neznama akce.";
            }
        }
    }

    protected function handleRegistrationForm()
    {

    }

    /**
     * Funkce vrati data uvodni stranky (implementovano v potomcich - kotrollerech)
     * @param string $pageTitle Nazev stranky
     * @return array Data predana sablone
     */
    abstract public function show(string $pageTitle): array;

}