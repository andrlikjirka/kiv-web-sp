<?php

namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
////// Sablona pro zobrazeni stranky spravy uzivatelu  ///////
/////////////////////////////////////////////////////////////

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

///////////// PRO NEPRIHLASENE UZIVATELE ///////////////
if ($tplData['isUserLoggedIn'] == false) {
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Spravovat uživatele může jen přihlášený uživatel.</div>";

    ///////////// KONEC: PRO NEPRIHLASENE UZIVATELE ///////////////

} else if ($tplData['isUserLoggedIn'] == true && $tplData['userData']['id_pravo'] > 2) {
    ///////////// PRO PRIHLASENE UZIVATELE BEZ PRAVA ADMIN ///////////////
    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Spravovat uživatele může jen přihlášený uživatel s pravem alespoň Admin.</div>";

    ///////////// KONEC: PRO PRIHLASENE UZIVATELE BEZ PRAVA ADMIN ///////////////
} else {
    ///////////// PRO PRIHLASENE UZIVATELE S PRAVEM ALESPON ADMIN///////////////
    ?>
    <!-- Sprava uzivatelu -->
    <section class="bg-light py-5">
        <div class="container py-5">
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">
                    <h3 class="text-dark"><?php echo $tplData['title'] ?> </h3>
                </div>
            </div>
            <div class="row mb-5 justify-content-center">
                <div class="col-lg-10">
                    <!-- Tabulka -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover caption-top align-middle ">
                            <caption>Tabulka uživatelů</caption>
                            <thead class="table-success border-0">
                            <th>ID</th>
                            <th>Jméno Příjmení</th>
                            <th>Login</th>
                            <th>Právo uživatele</th>
                            <th>Stav uživatele</th>
                            <th>Smazat uživatele</th>
                            </thead>

                            <?php
                            $users = $tplData['allUsers'];
                            foreach ($users as $user) {
                                $tableRows = "
                                <tr>
                                    <td". ($user['povolen'] == '0' ? ' class="text-danger"' : '') .">$user[id_uzivatel]</td>
                                    <td". ($user['povolen'] == '0' ? ' class="text-danger"' : '') .">$user[jmeno] $user[prijmeni]</td>
                                    <td". ($user['povolen'] == '0' ? ' class="text-danger"' : '') .">$user[login]</td>
                                    ";

                                if ($tplData['userData']['id_pravo'] < $user['id_pravo']) {
                                    $tableRows .= "<td><form action='' method='post'>
                                            <select class='form-select form-select-sm w-75' name = 'pravo' >";
                                                if ($tplData['userData']['id_pravo'] == 1) {
                                                    $tableRows .= "<option value = '2'" . ($user['id_pravo'] == '2' ? ' selected' : '') . ">Administrátor </option >";
                                                }

                                    $tableRows .= "
                                                <option value = 3" . ($user['id_pravo'] == '3' ? ' selected' : '') . ">Recenzent </option >
                                                <option value = 4" . ($user['id_pravo'] == '4' ? ' selected' : '') . ">Autor </option >
                                             </select >
                                             <input type='hidden' value='$user[id_uzivatel]' name='zmena_prava_id_uzivatel'>
                                             <button type='submit' class='mt-1 btn btn-warning  text-white btn-sm py-1 w-75' value='change'>Změnit právo</button>
                                        </form>
                                        </td>";

                                    $tableRows .=  "<td class=''>
                                            <form action='' method='post'>";
                                            if ($user['povolen'] == 1) {
                                                $tableRows .= "
                                                <input type='hidden' name='stav_id_uzivatel' value='$user[id_uzivatel]'>
                                                <input type='hidden' name='povolen' value='0'>
                                                <button type='submit' class='btn btn-secondary btn-sm text-white py-1 w-75'>Zablokovat</button>
                                                ";
                                            } else {
                                                $tableRows .= "
                                                <input type='hidden' name='stav_id_uzivatel' value='$user[id_uzivatel]'>
                                                <input type='hidden' name='povolen' value='1'>
                                                <button type='submit' class='btn btn-success btn-sm text-white py-1 w-75'>Povolit</button>
                                                ";
                                            }
                                    $tableRows .= "</form></td>";

                                    $tableRows .= "<td>
                                            <form action='' method='post'> 
                                                <input type='hidden' name='smazat_id_uzivatel' value='$user[id_uzivatel]'>
                                                <button type='submit' class='btn btn-danger btn-sm text-white py-1 w-75'>Smazat</button>
                                            </form>
                                        </td>";
                                } else {
                                    switch ($user['id_pravo']) {
                                        case 1:
                                            $tableRows .= "<td>SuperAdmin</td>";
                                            break;
                                        case 2:
                                            $tableRows .= "<td>Administrátor</td>";
                                    }
                                    $tableRows .= "<td></td><td></td>";
                                }
                                $tableRows .= "</tr>";

                                echo $tableRows;
                            }
                            ?>

                        </table>
                    </div>

                </div>
            </div>

        </div>
    </section>

    <?php
}









