<?php

namespace kivweb_sp\controllers;

/**
 * Trida reprezentujici kontroler stranky Moje clanky
 * @author jandrlik
 */
class MyArticlesController extends AController
{

    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
    public function show(string $pageTitle): array
    {
        $this->handleNewArticleForm();
        $this->handleEditArticleForm();
        $this->handleDeleteArticleForm();

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}