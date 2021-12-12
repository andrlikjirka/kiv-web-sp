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
        $this->handleLoginForm(); //zpracovani login formularu
        $this->handleLogoutForm(); //zpracovani logout formulare

        $tplData = $this->getData();
        //nazev stranky
        $tplData['title'] = $pageTitle;
        return $tplData;
    }
}
