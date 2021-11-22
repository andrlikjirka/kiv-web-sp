<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

class IntroductionController extends FormsController
{
    /** @var Database $db Objekt pripojeni k databazi */
    private $db;

    private $login;

    /**
     * Inicializace pripojeni k databazi
     */
    public function __construct(){
        $db = Database::getDBConnection();
        $this->login = new Login();
    }


    /**
     * Funkce vrati data uvodni stranky
     * @param string $pageTitle
     * @return array
     */
    public function show(string $pageTitle): array
    {
        global $tplData;
        $tplData = [];

        $this->handleLoginForm($this->login); //zpracovani login/logout formularu

        if ($this->login->isUserLoggedIn()) {
            //echo "logged in";
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
        } else {
            //echo "not logged in";
            $tplData['isUserLoggedIn'] = false;
        }

        //nazev stranky
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}
