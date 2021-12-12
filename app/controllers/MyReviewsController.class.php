<?php

namespace kivweb_sp\controllers;

/**
 * Trida reprezentujici kotnroler stranky Moje recenze
 * @author jandrlik
 */
class MyReviewsController extends AController
{
    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
    public function show(string $pageTitle): array
    {
        $this->handleUpdateReviewForm();

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}