<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;

class LoginController extends AController
{

    public function show(string $pageTitle): array
    {
        $this->handleLoginForm();

        $tplData = $this->getData();

        $tplData['title'] = $pageTitle;
        return $tplData;
    }
}
