<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

class LoginController extends FormsController
{
    /** @var Database $db Objekt pripojeni k databazi */
    private $db;

    private $login;

    public function __construct()
    {
        $this->db = Database::getDBConnection();
        $this->login = new Login();
    }



    public function show(string $pageTitle): array
    {
        global $tplData;
        $tplData = [];

        $this->handleLoginForm($this->login);

        if ($this->login->isUserLoggedIn()) {
            //echo "logged in";
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
        } else {
            //echo "not logged in";
            $tplData['isUserLoggedIn'] = false;
        }

        $tplData['title'] = $pageTitle;
        return $tplData;
    }
}
