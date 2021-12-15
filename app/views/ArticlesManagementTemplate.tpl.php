<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
////// Sablona pro zobrazeni stranky spravy clanku  ///////
/////////////////////////////////////////////////////////////


// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if ($tplData['isUserLoggedIn'] == false) {
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Spravovat články může jen přihlášený uživatel.</div>";

    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////

} else if ($tplData['isUserLoggedIn'] == true && $tplData['userData']['id_pravo'] > 2) {
    ///////////// PRO PRIHLASENE UZIVATELE BEZ PRAVA ADMIN ///////////////
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Spravovat články může jen přihlášený uživatel s pravem alespoň Admin.</div>";

    ///////////// KONEC: PRO PRIHLASENE UZIVATELE BEZ PRAVA ADMIN ///////////////

} else {
    ///////////// PRO PRIHLASENE UZIVATELE S PRAVEM ALESPON ADMIN///////////////

    ?>
    <!-- Sprava clanku -->
    <section class="bg-light py-5">
        <div class="container py-5">
            <div class="row mb-4 justify-content-center">
                <div class="col-lg-10">
                    <h3 class="text-dark"><?php echo $tplData['title'] ?> </h3>
                </div>
            </div>
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">
                    <?php
                    $prispevky = $tplData['prispevky'];
                    $uzivatele = $tplData['allUsers'];


                    /*foreach ($prispevky as $a) {
                        echo "Počet hodnocení: " . count($a['hodnoceni']) . "<br>";
                        echo "<pre>" . print_r($a) . "</pre>";
                    }*/

                    $recenzenti = [];
                    foreach ($uzivatele as $u) {
                        if ($u['id_pravo'] == 3 && $u['povolen'] == 1)
                            array_push($recenzenti, $u);
                    }

                    if (!empty($prispevky)) {

                        foreach ($prispevky as $prispevek) {
                            $pocetRecenzi = min($prispevek['poctyHodnoceni']); //pocet ulozenych recenzi (minimum z poctu recenzi obsahu, jazyka, odbornosti)
                            //echo $pocetRecenzi;

                            $dokument = UPLOADS_DIR . basename($prispevek['dokument'] . ".pdf");

                            $card = "
                            <div class='card bg-transparent mb-5 shadow-sm'>
                                <div class='card-body'>
                                    <div class='mb-3'>
                                        <span class='small'>Autor: " . $prispevek['autor']['jmenoPrijmeni'] . "</span><br>                                    
                                        <span class='small'>Datum: " . $prispevek['datum'] . "</span>
                                    </div>          
                                    <h5 class='card-title'>" . $prispevek['nadpis'] . "</h5>
                                    <p class='card-text'>"
                                . $prispevek['abstrakt']
                                . "</p>
                                <a href='$dokument' target='_blank' rel='noopener' class='small'>Zobrazit článek</a>    
                                                                    
                                </div>
                                <div class='card-footer'>
                                    <div class='row'>
                                        <div class='col-sm-12 col-md-6'>";
                            switch ($prispevek['id_status']) {
                                case STATUS_CEKA_NA_POSOUZENI:
                                    $card .= "<span class='badge bg-light text-dark mb-3'>Status: " . $prispevek['status'] . "</span><br>";
                                    break;
                                case STATUS_SCHVALIT:
                                    $card .= "<span class='badge bg-success mb-3'>Status: " . $prispevek['status'] . "</span><br>";
                                    break;
                                case STATUS_ZAMITNOUT:
                                    $card .= "<span class='badge bg-danger mb-3'>Status: " . $prispevek['status'] . "</span><br>";
                                    break;

                            }

                            /////// FORMULAR PRIRAZOVANI RECENZENTU ////////////
                            if ($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI && count($prispevek['hodnoceni']) < LIMIT_RECENZENTU) { //prispevek ceka na recenzi a zaroven nema zadny zaznam hodnoceni (zadny prirazeny recenzent)
                                $card .= "
                                    <form action='' method='post' class=''>
                                        <div class='input-group input-group-sm mb-3'>
                                            <!--<span class='input-group-text'>Přiřaď recenzenta</span>-->
                                            <select class='form-select form-select-sm' id='recenzenti' name='priradit_recenzenta_id_uzivatel'>";

                                foreach ($recenzenti as $recenzent) {
                                    $neprirazen = true;
                                    foreach ($prispevek['hodnoceni'] as $hodnoceni) {
                                        if ($recenzent['id_uzivatel'] == $hodnoceni['id_uzivatel']) $neprirazen = false;
                                    }
                                    if ($neprirazen == true) {
                                        $card .= "<option value='$recenzent[id_uzivatel]'>$recenzent[jmeno] $recenzent[prijmeni]</option>";
                                    }
                                }

                                $card .= "</select>
                                    <input type='hidden' name='priradit_recenzenta_id_prispevek' value='$prispevek[id_prispevek]'>
                                    <button type='submit' class='btn btn-sm btn-warning'>Přiřadit recenzenta</button>
                                    </div>
                                   </form>";
                            }

                            $card .= "
                                </div>
                                </div>
                                <div class='row'>
                                    <div class='col-sm-12 col-md-8'>   
                                
                                        <div class='table-responsive'>
                                        <table class='table table-sm'>
                                            <thead class='table-warning small'>
                                                <tr>
                                                    <th>Recenzent</th>                            
                                                    <th>Obsah</th>
                                                    <th>Jazyk</th>
                                                    <th>Odbornost</th>
                                                    <th style='width: 5%'></th>
                                                </tr>
                                            </thead>
                                            <tbody class='small'>";

                            foreach ($prispevek['hodnoceni'] as $hodnoceni) {
                                $card .= "
                                <tr>
                                    <td>$hodnoceni[recenzent]</td>
                                    <td>";
                                    for ($i = 0; $i < $hodnoceni['obsah']; $i++) {
                                        $card .= "<i class='bi bi-star-fill me-1 text-warning'></i>"; //vypis hvezdicek
                                    }
                                    $card .= "</td>
                                    <td>";
                                    for ($i = 0; $i < $hodnoceni['jazyk']; $i++) {
                                        $card .= "<i class='bi bi-star-fill me-1 text-warning'></i>"; //vypis hvezdicek
                                    }
                                    $card .= "</td>
                                    <td>";
                                    for ($i = 0; $i < $hodnoceni['odbornost']; $i++) {
                                        $card .= "<i class='bi bi-star-fill me-1 text-warning'></i>"; //vypis hvezdicek
                                    }
                                    $card .= "</td>
                                    <td>";

                                if ($prispevek['id_status'] == 1) { // odstranovat recenzenty lze jen ve stavu cekani na posouzeni
                                    $card .= "<form action='' method='post' class='m-0 p-0'>
                                            <input type='hidden' name='smazat_id_hodnoceni' value='$hodnoceni[id_hodnoceni]'>
                                            <button type='submit' class='btn btn-sm btn-outline-danger m-0 p-0' onclick='deleteReviewer(event)'>
                                                <i class='bi bi-x-circle p-1 m-0'></i>
                                            </button>
                                        </form>";
                                }
                                $card .= "</td>
                                </tr>
                            ";
                            }

                            if (count($prispevek['hodnoceni']) < LIMIT_RECENZENTU) {
                                $zbyva = LIMIT_RECENZENTU - count($prispevek['hodnoceni']);
                                $card .= "
                                <tr><td colspan='5'>
                                <div class='alert alert-warning small py-1 my-0' role='alert'>
                                    Zbývá přiřadit: " . $zbyva . "
                                </div>
                                </td></tr>

                            ";
                            }
                            $card .= "</tbody>
                                </table>
                                </div>
                                </div>
                            ";

                            $card .= "</div><hr>";

                            if ($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI) {
                                $card .= "<form action='' method='post' class='d-inline-block'> 
                                        <input type='hidden' name='schvalit_id_clanek' value='$prispevek[id_prispevek]'>
                                        <input type='hidden' name='schvalit_id_status' value='" . STATUS_SCHVALIT . "'>
                                        <button type='submit' class='btn btn-success btn-sm text-white me-1 py-1 text-white'
                                        " . ((($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI && $pocetRecenzi == LIMIT_RECENZENTU)) ? '' : 'disabled') . "
                                        onclick='approveArticle(event)'
                                        >
                                            <i class='bi bi-check-circle me-2'></i>
                                            Schválit článek
                                        </button>
                                    </form>";
                            }
                            if ($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI) {
                                $card .= "<form action='' method='post' class='d-inline-block'> 
                                        <input type='hidden' name='zamitnout_id_clanek' value='$prispevek[id_prispevek]'>
                                        <input type='hidden' name='zamitnout_id_status' value='" . STATUS_ZAMITNOUT . "'>
                                        <button type='submit' class='btn btn-danger btn-sm text-white me-1 py-1 text-white'
                                        " . ((($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI && $pocetRecenzi == LIMIT_RECENZENTU)) ? '' : 'disabled') . "
                                        onclick='rejectArticle(event)'>
                                            <i class='bi bi-x-circle me-2'></i>
                                            Zamítnout článek
                                        </button>
                                    </form>";
                            }
                            if ($prispevek['id_status'] == STATUS_SCHVALIT or $prispevek['id_status'] == STATUS_ZAMITNOUT) {
                                $card .= "<form action='' method='post' class='d-inline-block'> 
                                        <input type='hidden' name='znovu_posoudit_id_clanek' value='$prispevek[id_prispevek]'>
                                        <input type='hidden' name='znovu_posoudit_id_status' value='" . STATUS_CEKA_NA_POSOUZENI . "'>
                                        <button type='submit' class='btn btn-warning btn-sm text-white me-1 py-1 text-white'
                                        onclick='reviewAgainArticle(event)'>
                                            <i class='bi bi-x-circle me-2'></i>
                                            Znovu posoudit
                                        </button>
                                    </form>
                                    
                                    ";
                            }

                            $card .= "</div>
                        </div>";
                            echo $card;
                        }

                    } else {
                        $noArticles = "<p class='text-dark mt-3'>(Žádné články)</p>";
                        echo $noArticles;
                    }

                    ?>


                </div>
            </div>

        </div>
    </section>


    <script>
        /**
         * Funkce pri stistku tlacitka Smazat recenzenta spusti confirm box
         * @param event Udalost
         */
        function deleteReviewer(event) {
            if (!confirm("Opravdu chcete odstranit recenzenta?")) {
                event.preventDefault();
            }
        }

        /**
         * Funkce pri stisku tlacitka Schvalit spusti confirm box
         * @param event Udalost
         */
        function approveArticle(event) {
            if (!confirm("Opravdu chcete článek schválit?")) {
                event.preventDefault();
            }
        }

        /**
         * Funkce pri stisku tlacitka Zamitnout spusti confirm box
         * @param event Udalost
         */
        function rejectArticle(event) {
            if (!confirm("Opravdu chcete článek zamítnout?")) {
                event.preventDefault();
            }
        }
        /**
         * Funkce pri stisku tlacitka Zamitnout spusti confirm box
         * @param event Udalost
         */
        function reviewAgainArticle(event) {
            if (!confirm("Opravdu chcete znovu posoudit článek?")) {
                event.preventDefault();
            }
        }

    </script>

    <?php
}
