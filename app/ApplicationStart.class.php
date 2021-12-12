<?php

namespace kivweb_sp;

use kivweb_sp\controllers\IController;
use kivweb_sp\views\IView;

/**
 * Trida pro spusteni cele aplikace - podle pozadovane stranky zavola konstruktor, nacte sablonu a preda ji data, zajisti vypis sablony
 * @author jandrlik
 */
class ApplicationStart
{

    /**
     * Inicializace webove aplikace.
     */
    public function __construct()
    {
    }

    /**
     * Spusteni webove aplikace
     */
    public function appStart()
    {
        // test, zda je v URL pozadavku uvedena dostupna stranka, jinak volba defaultni stranky
        //mam spravnou hodnotu na vstupu nebo nastavim defaultni
        if (isset($_GET['page']) && array_key_exists($_GET['page'], WEB_PAGES)) {
            $pageKey = $_GET['page']; //nastavim pozadovane
        } else {
            $pageKey = DEFAULT_WEB_PAGE_KEY; //defaultni klic
        }

        //ziskam info o vybrane strance
        $pageInfo = WEB_PAGES[$pageKey];

        //nactu ovladac a bez ohledu na danou tridu ho typuji na rozhrani
        /** @var IController $controller Kontroler prislusne stranky */
        $controller = new $pageInfo["controller_class_name"];
        //zavolam prislusny ovladac a ziskam jeho obsah
        $tplData = $controller->show($pageInfo["title"]);

        //nactu sablonu a bez ohledu na prislusnou tridu ji typuji na dane rozhrani
        /** @var IView $view Sablona pro prislusne stranky */
        $view = new $pageInfo["view_class_name"];
        //zavolam sablonu, ktera primo vypisuje na svuj vystup
        //druhy parametr je pro TemplateBased sablony
        $view->printTemplate($tplData, $pageInfo["template_type"]);
    }

}