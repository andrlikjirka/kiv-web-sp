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
                        <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                data-bs-target="#novyClanekModal">
                            <i class="bi bi-plus-circle text-white me-2"></i>
                            Nový článek
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="novyClanekModal" data-bs-keyboard="false"
                             tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="staticBackdropLabel">Nový článek</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body px-5 py-4">

                                        <form action="" method="post" id="new-article" enctype="multipart/form-data">
                                            <div class="mb-3">
                                                <label for="new-nazev-clanku" class="form-label">Název článku</label>
                                                <input type="text" id="new-nazev-clanku" class="form-control"
                                                       name="nazev-clanku" required>
                                            </div>

                                            <div class="mb-3">
                                                <label for="new-abstrakt" class="form-label">Abstrakt</label>
                                                <textarea type="text" id="new-abstrakt" class="form-control" rows="8"
                                                          name="abstrakt" required></textarea>
                                            </div>

                                            <div class="mb-3">
                                                <label for="new-upload" class="form-label">PDF soubor s článkem</label>
                                                <input class="form-control" type="file" id="new-upload"
                                                       name="uploadFile"
                                                       required>
                                            </div>

                                            <input type="hidden" name="user_id"
                                                   value="<?php echo $tplData['userData']['id_uzivatel'] ?>">

                                        </form>

                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit
                                        </button>
                                        <button type="submit" form="new-article" class="btn btn-success" name="action"
                                                value="new-article">Přidat článek
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <?php

                    $prispevky = $tplData['prispevky'];
                    $loggedUser = $tplData['userData'];

                    if (!empty($prispevky)) {
                        foreach ($prispevky as $prispevek) {
                            $dokument = UPLOADS_DIR . basename($prispevek['dokument'] . ".pdf");
                            $card = "";

                            //$prispevek['abstrakt'] = htmlspecialchars_decode($prispevek['abstrakt']);

                            if ($prispevek['autor']['id_uzivatel'] == $loggedUser['id_uzivatel']) {
                                $card .= "
                            <div class='card bg-transparent my-4 shadow-sm'>
                                <div class='card-body'>
                                    <h5 class='card-title'>" . $prispevek['nadpis'] . "</h5>
                                    <div class='card-text'>"
                                    . $prispevek['abstrakt']
                                    . "</div>
                                    <a href='$dokument' target='_blank' rel='noopener' class='small'>Zobrazit článek</a>
                                    <!--<span class='small'>(" . $dokument . ")</span>-->
                                </div>
                                <div class='card-footer'>";

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
                                if ($prispevek['id_status'] == STATUS_SCHVALIT or $prispevek['id_status'] == STATUS_ZAMITNOUT) {
                                    $card .= "
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
                                            </tr>";
                                    }

                                    $card .= "          </tbody>
                                                    </table>
                                                </div>                                            
                                            
                                            </div>
                                        </div>
                                    ";
                                }

                                $card .= "
                                        
                                        <hr>
                                        <!-- Button trigger modal -->                                                     
                                        <button onclick=\"editArticle($prispevek[id_prispevek], '$prispevek[nadpis]', this)\" type='button' 
                                                class='btn btn-warning btn-sm py-1 text-white d-inline-block' data-bs-toggle='modal' data-bs-target='#editArticleModal'
                                                " . (($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI) ? '' : 'disabled') . ">
                                            <i class='bi bi-pencil-square me-2'></i>
                                            Upravit článek
                                        </button>
                                        <form action='' method='post' class='d-inline-block'> 
                                            <input type='hidden' name='smazat_id_clanek' value='$prispevek[id_prispevek]'>
                                            <button type='submit' class='btn btn-danger btn-sm text-white py-1 text-white' 
                                            " . (($prispevek['id_status'] == STATUS_CEKA_NA_POSOUZENI) ? '' : 'disabled') . "
                                            onclick='deleteArticle(event)'>
                                                <i class='bi bi-x-circle me-2'></i>
                                                Smazat článek
                                            </button>
                                        </form>      
                                </div>
                            </div>";

                            } else {
                                $card .= "
                                    
                                ";
                            }
                            echo $card;
                        }
                    }
                    ?>

                    <!-- Modal -->
                    <div class="modal fade" id="editArticleModal" data-bs-keyboard="false"
                         tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="staticBackdropLabel">Upravit článek</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                </div>
                                <div class="modal-body px-5 py-4">

                                    <form action="" method="post" id="edit-article" enctype="multipart/form-data">
                                        <div class="mb-3">
                                            <label for="edit-nazev-clanku" class="form-label">Upravený název
                                                článku</label>
                                            <input type="text" id="edit-nazev-clanku" class="form-control"
                                                   name="nazev-clanku" required>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit-abstrakt" class="form-label">Upravený abstrakt</label>
                                            <textarea type="text" id="edit-abstrakt" class="form-control" rows="8"
                                                      name="abstrakt" required></textarea>
                                        </div>

                                        <div class="mb-3">
                                            <label for="edit-upload" class="form-label">PDF soubor s upraveným
                                                článkem</label>
                                            <input class="form-control" type="file" id="edit-upload" name="editUploadFile">
                                        </div>

                                        <input type="hidden" name="article_id" id="article_id">
                                        <input type="hidden" name="user_id"
                                               value="<?php echo $tplData['userData']['id_uzivatel'] ?>">
                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Zrušit
                                    </button>
                                    <button type="submit" form="edit-article" class="btn btn-warning" name="action"
                                            value="edit-article">Upravit článek
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
        //místo předávání parametrů do onlclick funkce, vše brát pomocí JS ze stránky
        //ckeditor - setData


        function editArticle(id, nazev, button) {
            //console.log(abstrakt);
            document.getElementById("article_id").value = id;
            document.getElementById("edit-nazev-clanku").value = nazev;
            //document.getElementById("edit-abstrakt").value = abstrakt;
            console.log(button.parentElement.parentElement);

            var abstract = button.parentElement.parentElement.querySelector(".card-text").innerHTML;
            console.log(abstract);

            CKEDITOR.instances['edit-abstrakt'].setData(abstract);
        }
    </script>


    <script>

        CKEDITOR.replace('edit-abstrakt', {
            toolbar: [
                [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ],
                {name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] }
            ],
            removeButtons: ''
        });


        CKEDITOR.replace('new-abstrakt', {
            toolbar: [
                [ 'Cut', 'Copy', 'Paste', 'PasteText', '-', 'Undo', 'Redo' ],
                {name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'Subscript', 'Superscript', '-', 'CopyFormatting', 'RemoveFormat' ] }
            ],
            removeButtons: ''
        });


    </script>

    <?php


}