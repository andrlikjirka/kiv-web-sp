<?php

namespace kivweb_sp\controllers;

class MyArticlesController extends AController
{

    public function show(string $pageTitle): array
    {
        $this->handleNewArticleForm();
        $this->handleEditArticleForm();
        /*
        global $tplData;
        $tplData = [];


        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userArticles'] = $this->db->getArticlesbyUser($tplData['userData']['id_uzivatel']);
            $tplData['UPLOADS_DIR'] = ".\\uploads\\";

        } else {
            $tplData['isUserLoggedIn'] = false;
        }
        */
        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}