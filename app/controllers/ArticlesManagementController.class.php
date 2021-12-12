<?php

namespace kivweb_sp\controllers;

/**
 * Trida reprezentujici kontroler stranky Sprava clanku
 * @author jandrlik
 */
class ArticlesManagementController extends AController
{

    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
    public function show(string $pageTitle): array
    {

        $this->handleAddReviewerForm();
        $this->handleDeleteReviewerForm();

        $this->handleApproveArticleForm();
        $this->handleRejectArticleForm();
        $this->handleReviewAgainForm(); //znovu posouzeni

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}