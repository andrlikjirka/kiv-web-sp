<?php

namespace kivweb_sp\controllers;

class ArticlesManagementController extends AController
{

    public function show(string $pageTitle): array
    {
        /*global $tplData;
        $tplData = [];

        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['prispevky'] = $this->db->getAllArticles();
        } else {
            $tplData['isUserLoggedIn'] = false;
        }*/
        $this->handleAddReviewerForm();
        $this->handleDeleteReviewForm();
        $this->handleApproveArticleForm();
        $this->handleRejectArticleForm();
        $this->handleDeleteReviewsForm(); //znovu posouzeni

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}