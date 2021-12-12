<?php

namespace kivweb_sp\controllers;

class PublishedArticlesController extends AController
{

    public function show(string $pageTitle): array
    {

        $tplData = $this->getData();

        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}