<?php

namespace kivweb_sp\controllers;

/**
 * Trida reprezentujici kontroller pro zobrazeni clanku
 */
class ViewArticleController extends AController
{
    /**
     * Funkce predava pole dat ziskane z modelu do view
     * V tomto pripade pokud jsou splneny podminky clanek se vypise pres readfile(), pokud ne tak se vraci na index.php
     * @param string $pageTitle Nadpis stranky
     * @return array Zde metoda nevraci pole dat pro sablonu, protoze zobrazeni clanku probiha bez sablony
     */
    public function show(string $pageTitle): array
    {
        $tplData = $this->getData();
        $tplData['title'] = $pageTitle;

        $prispevky = $tplData['prispevky'];

        if (isset($_GET['dokument'])) {
            foreach ($prispevky as $prispevek) {
                if ($prispevek['dokument'] == $_GET['dokument']) {
                    if (($prispevek['id_status'] == STATUS_SCHVALIT) or
                        ($tplData['isUserLoggedIn'] and $prispevek['autor']['id_uzivatel'] == $tplData['userData']['id_uzivatel']) or
                        ($tplData['isUserLoggedIn'] and $tplData['userData']['id_pravo'] <= PRAVO_RECENZENT)
                    ) {
                        $file = UPLOADS_DIR . basename($prispevek['dokument'] . ".pdf");
                        if (file_exists($file)) {
                            // Header content type
                            header('Content-type: application/pdf');
                            header('Content-Disposition: inline; filename="' . $file . '"');
                            header('Content-Transfer-Encoding: binary');
                            header('Content-Length: ' . filesize($file));
                            header('Accept-Ranges: bytes');
                            readfile($file);
                        } else { //soubor neexistuje
                            header("Location: index.php?zobrazeni=chyba");
                        }
                    } else {
                        header("Location: index.php?zobrazeni=chyba");
                        die;
                    }
                }
            }
            // prosel jsem vsechny prispevky a zadny neni stejny jako v _GET => chyba
            header("Location: index.php?zobrazeni=chyba");
            die;

        } else {
            header("Location: index.php?zobrazeni=chyba");
            die;
        }

    }

}
