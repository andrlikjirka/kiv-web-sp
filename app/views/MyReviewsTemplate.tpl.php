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
                            $dokument = $tplData['UPLOADS_DIR'] . basename($r['dokument'] . ".pdf");

                            $card = "
                            <div class='card bg-transparent mb-5 shadow-sm'>
                                <div class='card-body'>
                                    <div class='mb-3'>
                                        <span class='small'>Autor: " . $r['autor'] . "</span><br>                                    
                                        <span class='small'>Datum: " . $r['datum'] . "</span>
                                    </div>          
                                    <h5 class='card-title'>" . $r['nadpis'] . "</h5>
                                    <p class='card-text'>"
                                . $r['abstrakt']
                                . "</p>
                               <a href='$dokument' target='_blank' class='small'>Zobrazit článek</a>
                                </div>
                                <div class='card-footer'>
                                    <div class='row'>
                                        <div class='col-sm-12 col-md-6'>";
                            switch ($r['id_status']) {
                                case 1:
                                    $card .= "<span class='badge bg-light text-dark mb-3'>Status: " . $r['status'] . "</span><br>";
                                    break;
                                case 2:
                                    $card .= "<span class='badge bg-success mb-3'>Status: " . $r['status'] . "</span><br>";
                                    break;
                                case 3:
                                    $card .= "<span class='badge bg-danger mb-3'>Status: " . $r['status'] . "</span><br>";
                                    break;
                            }

                            $card .= "
                                </div>
                                </div>
                            </div>
                        </div>";
                            echo $card;
                        }

                    } else {
                        $noReviews = "<p class='text-dark mt-3'>(Žádné recenze)</p>";
                        echo $noReviews;
                    }

                    ?>


                </div>
            </div>

        </div>
    </section>

    <?php


}
