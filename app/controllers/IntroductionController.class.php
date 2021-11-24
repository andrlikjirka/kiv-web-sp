<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

class IntroductionController extends AController
{
    /**
     * Funkce vrati data uvodni stranky
     * @param string $pageTitle Nazev stranky
     * @return array Data predana sablone
     */
    public function show(string $pageTitle): array
    {
        global $tplData;
        $tplData = [];

        $this->handleLoginForm(); //zpracovani login/logout formularu
        $this->handleRegistrationForm();

        if ($this->login->isUserLoggedIn()) {
            //echo "logged in";
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userRight'] = $this->db->getRightByID($tplData['userData']['id_pravo']);
        } else {
            //echo "not logged in";
            $tplData['isUserLoggedIn'] = false;
        }

        //nazev stranky
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}
