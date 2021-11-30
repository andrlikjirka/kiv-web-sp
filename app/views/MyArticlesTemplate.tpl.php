<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
////// Sablona pro zobrazeni stranky spravy uzivatelu  ///////
/////////////////////////////////////////////////////////////

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if ($tplData['isUserLoggedIn'] == false) {
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Psát články může jen přihlášený uživatel.</div>";

    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////
} else if ($tplData['isUserLoggedIn'] == true && $tplData['userData']['id_pravo'] != 4) {
    ///////////// PRO PRIHLASENE UZIVATELE BEZ PRAVA AUTOR ///////////////
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Psát články může jen přihlášený uživatel s pravem Autor.</div>";

    ///////////// KONEC: PRO PRIHLASENE UZIVATELE BEZ PRAVA AUTOR ///////////////

} else {
    ///////////// PRO PRIHLASENE UZIVATELE S PRAVEM AUTOR ///////////////

    ?>
    <!-- Moje clanky -->
    <section class="bg-light py-5">
        <div class="container py-5">
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">
                    <h3 class="text-dark"><?php echo $tplData['title'] ?> </h3>
                </div>
            </div>
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">

                    <div>
                        <!-- Button trigger modal -->
                        <button type="button" class="btn btn-success" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
                            <i class="bi bi-plus-circle text-white me-2"></i>
                            Nový článek
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Nový článek</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-5 py-4">

                                        <form action="" method="post" id="novy-clanek" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="lb-nazev-clanku" class="form-label">Název článku</label>
                                                <input type="text" id="lb-nazev-clanku" class="form-control" name="nazev-clanku" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="lb-abstrakt" class="form-label">Abstrakt</label>
                                                <textarea type="text" id="lb-abstrakt" class="form-control" rows="8" name="abstrakt" required></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="lb-upload" class="form-label">PDF soubor s článkem</label>
                                                <input class="form-control" type="file" id="lb-upload" name="uploadFile" required>
                                            </div>

                                            <input type="hidden" name="user_id" value="<?php echo $tplData['userData']['id_uzivatel'] ?>">

                                        </form>


                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit</button>
                                        <button type="submit" form="novy-clanek" class="btn btn-success" name="action" value="novy-clanek">Přidat článek</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div>

                        <?php

                        $articles = $tplData['userArticles'];

                        foreach ($articles as $a) {
                            echo "<pre>".print_r($a)."</pre>";
                        }


                        ?>


                    </div>




                </div>
            </div>

        </div>
    </section>

    <?php



}