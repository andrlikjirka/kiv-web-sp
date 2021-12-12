<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

/**
 * Trida reprezentujici kontroler stranky prihlaseni
 * @author jandrlik
 */
class LoginController extends AController
{

    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
    public function show(string $pageTitle): array
    {
        $this->handleLoginForm();

        $tplData = $this->getData();

        $tplData['title'] = $pageTitle;
        return $tplData;
    }
}
