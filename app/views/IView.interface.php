<?php

namespace kivweb_sp\views;

/**
 * Rozhrani pro sablony (views)
 */
interface IView
{
    /**
     * Funkce pro vypsani sablony prislusne stranky
     * @param array $templateData Data stranky
     * @return mixed Sablona stranky
     */
    public function printTemplate(array $templateData);
}

