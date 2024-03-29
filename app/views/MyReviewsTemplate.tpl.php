<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
////// Sablona pro zobrazeni stranky moje recenze  ///////
/////////////////////////////////////////////////////////////

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if ($tplData['isUserLoggedIn'] == false) {
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Psát recenze může jen přihlášený uživatel.</div>";

    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////

} else if ($tplData['isUserLoggedIn'] == true && $tplData['userData']['id_pravo'] != 3) {
    ///////////// PRO PRIHLASENE UZIVATELE BEZ PRAVA RECENZENT ///////////////
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Psát recenze může jen přihlášený uživatel s právem Recenzent.</div>";

    ///////////// KONEC: PRO PRIHLASENE UZIVATELE BEZ PRAVA RECENZENT ///////////////

} else {
    ///////////// PRO PRIHLASENE UZIVATELE S PRAVEM RECENZENT///////////////
    ?>

    <!-- Moje recenze -->
    <section class="bg-light py-5">
        <div class="container py-5">
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">
                    <h3 class="text-dark"><?php echo $tplData['title'] ?> </h3>
                </div>
            </div>
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">

                    <?php
                    $recenze = $tplData['userReviews'];
                    /*foreach ($recenze as $r) {
                        echo "<pre>" . print_r($r) . "</pre>";
                    }*/

                    if (!empty($recenze)) {

                        foreach ($recenze as $r) {
                            if (empty($r['obsah']) || empty($r['jazyk']) || empty($r['odbornost'])) {
                                $recenzovano = false;
                            } else {
                                $recenzovano = true;
                            }

                            $card = "
                            <div class='card bg-transparent mb-5 shadow-sm'>
                                <div class='card-body'>
                                    <div class='mb-3'>
                                        <span class='small'>Autor: " . $r['autor'] . "</span><br>                                    
                                        <span class='small'>Datum: " . $r['datum'] . "</span>
                                    </div> 
                                    <h5 class='card-title'>" . $r['nadpis'] . "</h5>
                                    <p class='card-text'>" . htmlspecialchars_decode($r['abstrakt']) . "</p>
                               <a href='index.php?page=zobrazeni&dokument=".$r['dokument']."' target='_blank' class='small'>Zobrazit článek</a>
                                </div>
                                <div class='card-footer'>
                                    ";
                            switch ($r['id_status']) {
                                case STATUS_CEKA_NA_POSOUZENI:
                                    $card .= "<span class='badge bg-light text-dark mb-3'>Status: " . $r['status'] . "</span><br>";
                                    break;
                                case STATUS_SCHVALIT:
                                    $card .= "<span class='badge bg-success mb-3'>Status: " . $r['status'] . "</span><br>";
                                    break;
                                case STATUS_ZAMITNOUT:
                                    $card .= "<span class='badge bg-danger mb-3'>Status: " . $r['status'] . "</span><br>";
                                    break;
                            }

                            if ($recenzovano == false) {
                                $card .= "
                            <!-- Button trigger modal -->                                                     
                            <button onclick=\"recenzovat('$r[id_hodnoceni]')\" type='button' 
                                    class='btn btn-warning btn-sm py-1 d-inline-block text-white' data-bs-toggle='modal' data-bs-target='#recenzeModal'
                                    " . (($r['id_status'] == STATUS_CEKA_NA_POSOUZENI) ? '' : 'disabled') . ">
                                <i class='bi bi-star-fill me-2'></i>
                                Recenzovat článek
                            </button> 
                            ";
                            } else {
                                $card .= "
                                <div class='row'>
                                    <div class='col-sm-12 col-md-6'>
                                        <div class='table-responsive'>
                                            <table class='table table-sm'>
                                                <thead class='table-warning small'>
                                                    <tr>
                                                        <th>Obsah</th>
                                                        <th>Jazyk</th>
                                                        <th>Odbornost</th>
                                                    </tr>
                                                </thead>
                                                <tbody class='small'>
                                                    <tr>
                                                        <td>";
                                for ($i = 0; $i < $r['obsah']; $i++) {
                                    $card .= "<i class='bi bi-star-fill me-1 text-warning'></i>";
                                }

                                $card .= "</td>
                                                        <td>";
                                for ($i = 0; $i < $r['jazyk']; $i++) {
                                    $card .= "<i class='bi bi-star-fill me-1 text-warning'></i>";
                                }
                                $card .= "</td>
                                                        <td>";
                                for ($i = 0; $i < $r['odbornost']; $i++) {
                                    $card .= "<i class='bi bi-star-fill me-1 text-warning'></i>";
                                }
                                $card .= "</td>
                                                    </tr>
                                                </tbody>
                                                </table>
                                         </div>
                                    </div>
                                </div>";

                                $card .= "
                            <!-- Button trigger modal -->                                                     
                            <button onclick=\"upravitRecenzi('$r[id_hodnoceni]', '$r[obsah]', '$r[jazyk]', '$r[odbornost]')\" type='button'
                                    class='btn btn-warning btn-sm py-1 d-inline-block text-white' data-bs-toggle='modal' data-bs-target='#recenzeModal'
                                    " . (($r['id_status'] == STATUS_CEKA_NA_POSOUZENI) ? '' : 'disabled') . ">
                                <i class='bi bi-star-fill me-2 text-white'></i>
                                Upravit recenzi
                            </button> 
                            ";
                            }

                            $card .= "
                                </div>
                            </div>";
                            echo $card;
                        }

                    } else {
                        $noReviews = "<p class='text-dark mt-3'>(Žádné recenze)</p>";
                        echo $noReviews;
                    }

                    ?>

                    <!-- Modal -->
                    <div class="modal fade" id="recenzeModal" data-bs-keyboard="false"
                         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-sm modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Recenzovat článek</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-5 py-4">
                                    <p>Přiřaďte ke všem kategoriím počet hvězdiček.</p>
                                    <form action='' method='post' id="recenze">
                                        <div class='input-group input-group-sm mb-3'>
                                            <span class='input-group-text w-50' id='obsah'>Obsah</span>
                                            <input type='number' class='form-control' id="input-obsah" name="obsah"
                                                   aria-label='Obsah' aria-describedby='obsah' min='1' max='5' step='1'
                                                   required>
                                        </div>
                                        <div class='input-group input-group-sm mb-3'>
                                            <span class='input-group-text w-50' id='jazyk'>Jazyk</span>
                                            <input type='number' class='form-control' name="jazyk" id="input-jazyk"
                                                   aria-label='Jazyk' aria-describedby='jazyk' min='1' max='5' step='1'
                                                   required>
                                        </div>
                                        <div class='input-group input-group-sm mb-3'>
                                            <span class='input-group-text w-50' id='obsah'>Odbornost</span>
                                            <input type='number' class='form-control' name="odbornost"
                                                   id="input-odbornost"
                                                   aria-label='Odbornost' aria-describedby='odbornost' min='1' max='5'
                                                   step='1' required>
                                        </div>
                                        <input type="hidden" name="id_hodnoceni" id="id_hodnoceni">
                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit
                                    </button>
                                    <button type="submit" form="recenze" class="btn btn-warning text-white"
                                            name="action"
                                            value="ulozit-recenzi">Uložit recenzi
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </section>


    <script>
        /**
         * Funkce po stisku tlacitka 'Recenzovat' priradi id_hodnoceni do hidden inputu prislusneho modalu
         * @param id_hodnoceni ID recenze
         */
        function recenzovat(id_hodnoceni) {
            document.getElementById('id_hodnoceni').value = id_hodnoceni;
        }

        /**
         * Funkce po stisku tlacitka 'Upravit recenzi' priradi potrebna data do prislusneho modalu
         * @param id_hodnoceni ID recenze
         * @param obsah Hodnoceni obsahu
         * @param jazyk Hodnoceni jazyka
         * @param odbornost Hodnoceni odbornosti
         */
        function upravitRecenzi(id_hodnoceni, obsah, jazyk, odbornost) {
            document.getElementById('id_hodnoceni').value = id_hodnoceni;
            document.getElementById('input-obsah').value = obsah;
            document.getElementById('input-jazyk').value = jazyk;
            document.getElementById('input-odbornost').value = odbornost;
        }


    </script>

    <?php

}
