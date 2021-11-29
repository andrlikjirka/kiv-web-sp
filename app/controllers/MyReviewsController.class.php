<?php

namespace kivweb_sp\controllers;

class MyReviewsController extends AController
{

    public function show(string $pageTitle): array
    {
        global $tplData;
        $tplData = [];

        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userRight'] = $this->db->getRightByID($tplData['userData']['id_pravo']);
        } else {
            $tplData['isUserLoggedIn'] = false;
        }
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}