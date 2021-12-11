<?php

namespace kivweb_sp\controllers;

class MyReviewsController extends AController
{



    public function show(string $pageTitle): array
    {
        $this->handleUpdateReviewForm();

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}