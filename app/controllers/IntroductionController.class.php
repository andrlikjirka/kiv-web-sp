<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;

class IntroductionController implements IController
{
    /** @var Database $db Objekt pripojeni k databazi */
    private $db;

    /**
     * Inicializace pripojeni k databazi
     */
    public function __construct(){
        //$db = Database::getDBConnection();
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
        //nazev stranky
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}