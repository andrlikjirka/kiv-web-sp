<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

class LoginController extends AController
{

    public function show(string $pageTitle): array
    {
        global $tplData;
        $tplData = [];

        $this->handleLoginForm();

        if ($this->login->isUserLoggedIn()) {
            //echo "logged in";
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userRight'] = $this->db->getRightByID($tplData['userData']['id_pravo']);

        } else {
            //echo "not logged in";
            $tplData['isUserLoggedIn'] = false;
        }

        $tplData['title'] = $pageTitle;
        return $tplData;
    }
}
