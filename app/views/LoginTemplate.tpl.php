<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni login stranky  ///////////
/////////////////////////////////////////////////////////////
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////

if ($tplData['isUserLoggedIn'] == false) {
    ?>

    <!-- Login -->
    <section class="bg-success bg-gradient pt-5">
        <div class="container px-5 py-5">
            <div class="row justify-content-center">
                <div class="col-lg-6 col-md-8 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3 px-4 text-success">Přihlásit</div>
                        <div class="card-body py-4 px-4">
                            <form action=""
                                  method="post" autocomplete="off">
                                <div class="mb-3">
                                    <label for="lb_username" class="form-label small">Uživatelské jméno</label>
                                    <input type="text" class="form-control" id="lb_username" name="login" autofocus
                                           required>
                                </div>
                                <div class="mb-4">
                                    <label for="lb_password" class="form-label small">Heslo</label>
                                    <input type="password" class="form-control" id="lb_password" name="heslo" required>
                                </div>
                                <input type="hidden" name="action" value="login">
                                <button type="submit" name="potvrzeni" class="btn btn-success mt-1 px-4">Přihlásit se
                                </button>
                                <!--
                                <p class="mt-4 small">
                                    Ještě nemáte vytvořený účet?
                                    <a class="text-success" href="index.php?page=registrace">Registrovat se</a>
                                </p>
                                -->
                            </form>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <?php
    ///////////// PRO PRIHLASENE UZIVATELE ///////////////
} else {
    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Přihlášený uživatel.</div>";
    echo "Login: " . $tplData['userData']['login'] . "<br>";
    echo "Jméno, příjmení: " . $tplData['userData']['jmeno'] . " " . $tplData['userData']['prijmeni'] . "<br>";
    echo "E-mail: " . $tplData['userData']['email'] . "<br>";
    echo "Právo: " . $tplData['userData']['nazevPravo'] . "<br>";

}

