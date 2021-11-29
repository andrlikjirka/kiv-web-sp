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

                </div>
            </div>

        </div>
    </section>

    <?php



}
