<?php

namespace kivweb_sp\controllers;

/**
 * Trida reprezentujici rozhrani kontroleru, obsahuje signaturu metody
 * @author jandrlik
 */
interface IController
{
    /**
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formualre
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
    public function show(string $pageTitle): array;
}