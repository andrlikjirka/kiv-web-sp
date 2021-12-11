<?php

namespace kivweb_sp\controllers;

class MyArticlesController extends AController
{

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