<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Login;

abstract class FormsController implements IController
{

    public function handleLoginForm(Login $login)
    {
        // zpracovani odeslanych formularu
        if (isset($_POST['action'])) {
            //prihlaseni
            if ($_POST['action'] == 'login' && isset($_POST['login']) && isset($_POST['heslo'])) {
                //pokusim se prihlasit uzivatele
                $result = $login->login($_POST['login'], $_POST['heslo']);
                if ($result) {
                    echo "OK: Uživatel byl přihlášen.";
                } else {
                    echo "ERROR: Přihlášení uživatele se nezdařilo";
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Špatné jméno nebo heslo.</div>";
                }
            }
            //odhlaseni
            else if ($_POST['action'] == 'logout') {
                //odhlasim uzivatele
                $login->logout();
                echo "OK: Uživatel byl odhlášen.";
            }
            //neznama akce
            else {
                echo "WARNING: Neznama akce.";
            }
        }
    }

}