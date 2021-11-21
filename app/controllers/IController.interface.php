<?php

namespace kivweb_sp\controllers;

interface IController
{
    /**
     * Zajisti vypsani prislusne stranky
     * @param string $pageTitle Nazev stranky
     * @return array Vraci pole hodnot ziskane z modelu, ktere predava do view
     */
    public function show(string $pageTitle): array;
}