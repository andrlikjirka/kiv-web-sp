<?php

namespace kivweb_sp\controllers;

class UserManagementController extends AController
{

    public function show(string $pageTitle): array
    {
        $this->handleDeleteUserForm();
        $this->handleChangeRoleForm();
        $this->handleBlockAllowUserForm();

        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}
