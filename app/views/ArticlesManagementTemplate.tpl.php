<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
////// Sablona pro zobrazeni stranky spravy clanku  ///////
/////////////////////////////////////////////////////////////
const LIMIT_RECENZENTU = 3;
const STATUS_CEKA_NA_POSOUZENI = 1;
const STATUS_SCHVALIT = 2;
const STATUS_ZAMITNOUT = 3;

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

                    foreach ($prispevky as $prispevek) {
                        $pocetRecenzi = min($prispevek['poctyHodnoceni']); //pocet ulozenych recenzi (minimum z poctu recenzi obsahu, jazyka, odbornosti)
                        //echo $pocetRecenzi;

                        $card = "
                            <div class='card bg-transparent mb-5 shadow-sm'>
                                <div class='card-body'>
                                    <div class='mb-3'>
                                        <span class='small'>Autor: " . $prispevek['autor'] . "</span><br>                                    
                                        <span class='small'>Datum: " . $prispevek['datum'] . "</span>
                                    </div>          
                                    <h5 class='card-title'>" . $prispevek['nadpis'] . "</h5>
                                    <p class='card-text'>"
                            . $prispevek['abstrakt']
                            . "</p>
                                    
                                </div>
                                <div class='card-footer'>
                                    <div class='row'>
                                        <div class='col-sm-12 col-md-6'>";
                        switch ($prispevek['id_status']) {
                            case 1:
                                $card .= "<span class='badge bg-light text-dark mb-3'>Status: " . $prispevek['status'] . "</span><br>";
                                break;
                            case 2:
                                $card .= "<span class='badge bg-success mb-3'>Status: " . $prispevek['status'] . "</span><br>";
                                break;
                            case 3:
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
                                                    <th>Odbornost</th>                                            
                                                    <th>Obsah</th>
                                                    <th>Jazyk</th>
                                                    <th style='width: 5%'></th>
                                                </tr>
                                            </thead>
                                            <tbody class='small'>";

                        foreach ($prispevek['hodnoceni'] as $hodnoceni) {
                            $card .= "
                                <tr>
                                    <td>$hodnoceni[recenzent]</td>
                                    <td>$hodnoceni[odbornost]</td>
                                    <td>$hodnoceni[obsah]</td>
                                    <td>$hodnoceni[jazyk]</td>
                                    <td>";
                            if ($prispevek['id_status'] == 1) { // odstranovat recenzenty lze jen ve stavu cekani na posouzeni
                                $card .= "<form action='' method='post' class='m-0 p-0'>
                                            <input type='hidden' name='smazat_id_hodnoceni' value='$hodnoceni[id_hodnoceni]'>
                                            <button type='submit' class='btn btn-sm btn-outline-danger m-0 p-0'>
                                                <i class='bi bi-x-circle p-1 m-0'></i>
                                            </button>
                                        </form>";
                            }
                            $card .= "</td>
                                </tr>
                            ";
                        }

                        if (count($prispevek['hodnoceni']) < 3) {
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
                                        " . ((($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI && $pocetRecenzi == LIMIT_RECENZENTU)) ? '' : 'disabled') . ">
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
                                        " . ((($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI && $pocetRecenzi == LIMIT_RECENZENTU)) ? '' : 'disabled') . ">
                                            <i class='bi bi-x-circle me-2'></i>
                                            Zamítnout článek
                                        </button>
                                    </form>";
                        }
                        if ($prispevek['id_status'] == STATUS_SCHVALIT or $prispevek['id_status'] == STATUS_ZAMITNOUT) {
                            $card .= "<form action='' method='post' class='d-inline-block'> 
                                        <input type='hidden' name='znovu_posoudit_id_clanek' value='$prispevek[id_prispevek]'>
                                        <input type='hidden' name='znovu_posoudit_id_status' value='" . STATUS_CEKA_NA_POSOUZENI . "'>
                                        <button type='submit' class='btn btn-warning btn-sm text-white me-1 py-1 text-white'>
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


                    ?>


                </div>
            </div>

        </div>
    </section>


    <script>
        if (window.history.replaceState) {
            window.history.replaceState(null, null, window.location.href);
        }
    </script>

    <?php


}
