<?php

namespace kivweb_sp\controllers;

class RegistrationController extends AController
{

    public function show(string $pageTitle): array
    {
        $this->handleRegistrationForm();

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;
        return $tplData;

    }
}