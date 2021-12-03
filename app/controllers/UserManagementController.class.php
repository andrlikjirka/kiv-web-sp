<?php

namespace kivweb_sp\controllers;

class UserManagementController extends AController
{

    public function show(string $pageTitle): array
    {
        $this->handleDeleteUserForm();
        $this->handleChangeRoleForm();
        $this->handleBlockAllowUserForm();
        /*global $tplData;
        $tplData = [];



        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userRight'] = $this->db->getRightByID($tplData['userData']['id_pravo']);
        } else {
            $tplData['isUserLoggedIn'] = false;
        }
        $tplData['allUsers'] = $this->db->getAllUsers();
        */
        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        return $tplData;
    }
}