<?php

namespace kivweb_sp\controllers;

/**
 * Trida reprezentujici kontroler stranky Sprava uzivatelu
 * @author jandrlik
 */
class UserManagementController extends AController
{
    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
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
