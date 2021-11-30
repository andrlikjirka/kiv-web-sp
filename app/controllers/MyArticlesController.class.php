<?php

namespace kivweb_sp\controllers;

class MyArticlesController extends AController
{

    public function show(string $pageTitle): array
    {
        global $tplData;
        $tplData = [];

        $this->handleNewArticleForm();

        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userArticles'] = $this->db->getArticlesbyUser($tplData['userData']['id_uzivatel']);
        } else {
            $tplData['isUserLoggedIn'] = false;
        }
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}