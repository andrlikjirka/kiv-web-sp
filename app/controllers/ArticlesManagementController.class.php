<?php

namespace kivweb_sp\controllers;

class ArticlesManagementController extends AController
{

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