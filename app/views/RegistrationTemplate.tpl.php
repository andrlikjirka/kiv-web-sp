<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
/////////// Sablona pro zobrazeni registracni stranky  ///////////
/////////////////////////////////////////////////////////////
// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if ($tplData['isUserLoggedIn'] == false) {
    ?>

    <!-- Registrace -->
    <section class="bg-success bg-gradient pt-5">
        <div class="container px-5 py-5">
            <div class="row justify-content-center">
                <div class="col-lg-8 col-md-12 col-sm-12">
                    <div class="card">
                        <div class="card-header py-3 px-4 text-success">Registrace autora</div>
                        <div class="card-body py-4 px-4">
                            <form action=""
                                  oninput="overeniHesel()"
                                  method="post"
                                  autocomplete="off">
                                <!-- Jméno, Uživatelské jméno -->
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="lb_fname" class="form-label small">
                                                Jméno
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="jmeno" id="lb_fname" autofocus
                                                   required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="lb_lname" class="form-label small">
                                                Příjmení
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="prijmeni" id="lb_lname"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="lb_login" class="form-label small">
                                                Uživatelské jméno
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="text" class="form-control" name="login" id="lb_login" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="lb_email" class="form-label small">
                                                E-mail
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="email" class="form-control" name="email" id="lb_email"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <!-- Heslo, potvrzení hesla -->
                                <div class="row align-items-center">
                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-3">
                                            <label for="lb_password" class="form-label small">
                                                Heslo
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control" name="password1"
                                                   id="lb_password" required>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6">
                                        <div class="mb-4">
                                            <label for="lb_confirm_password" class="form-label small">
                                                Potvrzení hesla
                                                <span class="text-danger">*</span>
                                            </label>
                                            <input type="password" class="form-control" name="password2"
                                                   id="lb_confirm_password"
                                                   required>
                                        </div>
                                    </div>
                                </div>
                                <div class="mb-3 small">
                                    <span>Ověření hesla: </span>
                                    <output id="overeni-hesla" name="overeni" for="password1, password2"></output>
                                </div>
                                <button type="submit" name="registrace" class="btn btn-success mt-1 px-4">Registrovat
                                    se
                                </button>

                                <!--
                                <p class="mt-4 small">
                                    Již máte vytvořený účet?
                                    <a class="text-success" href="">Přihlaste se</a>
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
} else {
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Přihlášený uživatel se nemůže znovu registrovat.</div>";
    echo "Login: " . $tplData['userData']['login'] . "<br>";
    echo "Jméno, příjmení: " . $tplData['userData']['jmeno'] . " " . $tplData['userData']['prijmeni'] . "<br>";
    echo "E-mail: " . $tplData['userData']['email'] . "<br>";
    echo "Pravo: " . $tplData['userData']['nazevPravo'] . "<br>";

}