<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

/**
 * Trida reprezentujici kontroler uvodni stranky
 * @author jandrlik
 */
class IntroductionController extends AController
{
    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
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
