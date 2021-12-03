<?php

namespace kivweb_sp\controllers;

use http\Header;
use kivweb_sp\models\Database;
use kivweb_sp\models\Login;
use kivweb_sp\models\Registration;

abstract class AController implements IController
{

    /** @var Database $db Objekt pripojeni k databazi */
    protected $db;

    protected $login;

    protected $registration;

    public function __construct()
    {
        $this->db = Database::getDBConnection();
        $this->login = new Login();
        $this->registration = new Registration();
    }

    protected function handleLoginForm()
    {
        // zpracovani odeslanych formularu
        if (isset($_POST['action'])) {
            //prihlaseni
            if ($_POST['action'] == 'login' && isset($_POST['login']) && isset($_POST['heslo'])) {
                //pokusim se prihlasit uzivatele
                $result = $this->login->login($_POST['login'], $_POST['heslo']);
                if ($result) {
                    header('Location: index.php');
                    echo "OK: Uživatel byl přihlášen.";
                } else {
                    //echo "ERROR: Přihlášení uživatele se nezdařilo";
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Nesprávné jméno nebo heslo.</div>";
                }
            }
        }
    }

    protected function handleLogoutForm()
    {
        if (isset($_POST['action'])) {
            //odhlaseni
            if ($_POST['action'] == 'logout') {
                //odhlasim uzivatele
                $this->login->logout();
                echo "OK: Uživatel byl odhlášen.";
            } //neznama akce
            else {
                echo "WARNING: Neznama akce.";
            }
        }
    }

    protected function handleRegistrationForm()
    {
        // zpracovani odeslaneho registracniho formulare
        if (isset($_POST['registrace'])) {
            //mam vsechny pozadovane hodnoty?
            if (isset($_POST['login']) && isset($_POST['password1']) && isset($_POST['password2'])
                && isset($_POST['jmeno']) && isset($_POST['prijmeni']) && isset($_POST['email'])
                && $_POST['password1'] == $_POST['password2']
                && $_POST['login'] != "" && $_POST['password1'] != "" && $_POST['jmeno'] != "" && $_POST['prijmeni'] != "" && $_POST['email'] != ""
            ) {
                if ($this->db->isUserWithLogin($_POST['login']) == false) { //neexistuje v db uzivatel se zadanym loginem
                    $hash_password = password_hash($_POST['password1'], PASSWORD_BCRYPT);
                    $result = $this->registration->registrateUser($_POST['jmeno'], $_POST['prijmeni'], $_POST['login'], $hash_password, $_POST['email']);
                    if ($result) {
                        //echo "OK: Uživatel byl přidán do databáze.";
                        echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Registrace uživatele proběhla úspěšně.</div>";
                    } else {
                        //echo "ERROR: Uložení uživatele do databáze se nezdařilo";
                        echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Registrace uživatele se nezdařila.</div>";
                    }
                } else {
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Uživatel se zadaným uživatelským jménem již existuje.</div>";
                }

            } else {
                //nemam vsechny atributy
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Registrace uživatele se nezdařila.</div>";
            }
        }
    }

    protected function handleDeleteUserForm()
    {
        // zpracovani odeslaneho formulare pro odstraneni uzivatele
        if (isset($_POST['smazat_id_uzivatel'])) {
            //smazu daneho uzivatele z databaze
            $res = $this->db->deleteUser($_POST['smazat_id_uzivatel']);
            //vysledek maazani
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Smazání uživatele proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Smazání uživatele se nezdařilo.</div>";
            }
        }
    }

    protected function handleChangeRoleForm()
    {
        // zpracovani odeslaneho formulare pro odstraneni uzivatele
        if (isset($_POST['zmena_prava_id_uzivatel']) && isset($_POST['pravo'])) {
            $res = $this->db->updateUserRight($_POST['zmena_prava_id_uzivatel'], $_POST['pravo']);
            if ($res == null) {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Změna práva uživatele proběhla neúspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Změna práva uživatele proběhla úspěšně.</div>";
            }
        }
    }

    protected function handleBlockAllowUserForm()
    {
        if (isset($_POST['stav_id_uzivatel'])) {
            $res = $this->db->updateBlockAllowUser($_POST['stav_id_uzivatel'], $_POST['povolen']);
            if ($res == null) {
                if ($_POST['povolen'] == 0) {
                    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Zablokování uživatele proběhla neúspěšně.</div>";
                } else {
                    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Povolení uživatele proběhla neúspěšně.</div>";
                }
            } else {
                if ($_POST['povolen'] == 0) {
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Zablokování uživatele proběhla úspěšně.</div>";
                } else {
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Povolení uživatele proběhla úspěšně.</div>";
                }
            }
        }
    }

    protected function handleNewArticleForm()
    {
        $UPLOADS_DIR = getcwd() . DIRECTORY_SEPARATOR . "uploads\\";

        if (isset($_POST['action']) && $_POST['action'] == 'new-article'
            && $_POST['nazev-clanku'] != "" && !empty($_POST['user_id']) && $_POST['abstrakt'] != "" && $_FILES['uploadFile']['type'] == 'application/pdf'
        ) {
            $uploadDate = date("Y-m-d G:i:s");
            $uploadDateTime = date("Y-m-d") . "_" . time();
            $articleName = $_POST['user_id'] . "_" . $uploadDateTime;
            $target = $UPLOADS_DIR . basename($articleName . ".pdf");

            $res = $this->db->addNewArticle($_POST['nazev-clanku'], $_POST['abstrakt'], $articleName, $uploadDate, $_POST['user_id']);
            if ($res) {
                move_uploaded_file($_FILES['uploadFile']['tmp_name'], $target);
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Přidání příspěvku proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Přidání příspěvku se nezdařilo.</div>";
            }

        }
    }

    protected function handleEditArticleForm()
    {

        if (isset($_POST['action']) && $_POST['action'] == 'edit-article'
            && $_POST['nazev-clanku'] != "" && !empty($_POST['article_id']) && $_POST['abstrakt'] != ""
        ) {
            if (isset($_POST['uploadFile']) && $_FILES['uploadFile']['type'] == 'application/pdf') {
                //upraveny soubor nahran

            } else {
                //upraveny soubor nenahran
                $res = $this->db->updateArticle($_POST['article_id'], $_POST['nazev-clanku'], $_POST['abstrakt']);
                if ($res) {
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Úprava příspěvku proběhlo úspěšně.</div>";
                } else {
                    echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Úprava příspěvku se nezdařila.</div>";
                }
            }
        }
    }


    protected function handleAddReviewerForm()
    {
        if (isset($_POST['priradit_recenzenta_id_uzivatel']) && isset($_POST['priradit_recenzenta_id_prispevek'])) {
            //priradim recenezenta
            $res = $this->db->addNewReview($_POST['priradit_recenzenta_id_uzivatel'], $_POST['priradit_recenzenta_id_prispevek']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Přiřazení recenzenta proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Přiřazení recenzenta se nezdařilo.</div>";
            }

        }
    }

    protected function handleDeleteReviewForm()
    {
        if (isset($_POST['smazat_id_hodnoceni'])) {
            $res = $this->db->deleteReview($_POST['smazat_id_hodnoceni']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Smazání recenzenta proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Smazání recenzenta se nezdařilo.</div>";
            }
        }
    }

    protected function handleApproveArticleForm()
    {
        if (isset($_POST['schvalit_id_clanek']) && isset($_POST['schvalit_id_status'])) {
            $res = $this->db->changeArticleStatus($_POST['schvalit_id_clanek'], $_POST['schvalit_id_status']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Schválení článku proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Schválení článku proběhlo neúspěšně.</div>";
            }
        }
    }

    protected function handleRejectArticleForm()
    {
        if (isset($_POST['zamitnout_id_clanek']) && isset($_POST['zamitnout_id_status'])) {
            $res = $this->db->changeArticleStatus($_POST['zamitnout_id_clanek'], $_POST['zamitnout_id_status']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Zamítnutí článku proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Zamítnutí článku proběhlo neúspěšně.</div>";
            }
        }
    }

    public function handleReviewAgainForm()
    {
        if (isset($_POST['znovu_posoudit_id_clanek']) && isset($_POST['znovu_posoudit_id_status'])) {
            $res = $this->db->changeArticleStatus($_POST['znovu_posoudit_id_clanek'], $_POST['znovu_posoudit_id_status']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Článek je znovu možné posoudit.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Článek není znovu možné posoudit.</div>";
            }
        }
    }

    protected function getData()
    {
        global $tplData;
        $tplData = [];

        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            if ($tplData['userData']['id_pravo'] == 4) { //prihlaseny uzivatel je autor
                $tplData['userArticles'] = $this->db->getArticlesByUser($tplData['userData']['id_uzivatel']);
            }
            if ($tplData['userData']['id_pravo'] == 3) { //prihlaseny uzivatel je recenzent
                $tplData['userReviews'] = $this->db->getReviewsByUser($tplData['userData']['id_uzivatel']);
            }
            //if prihlaseny uzivatel je alespon admin tak getAllUsers, getAllArticles ???
            $tplData['UPLOADS_DIR'] = ".\\uploads\\";

        } else {
            $tplData['isUserLoggedIn'] = false;
        }

        $tplData['allUsers'] = $this->db->getAllUsers(); //POZOR: na ziskavani vsech data vcetne hesel

        //je nutne ziskavat info pro vsechny uzivatele?
        //nestacilo by jen pro prihlaseneho?
        /*
        for ($i = 0; $i < count($tplData['users']); $i++) {
            $tplData['users'][$i]['pravo'] = $this->db->getUserRight($tplData['users'][$i]['id_uzivatel']);

            if ($tplData['users'][$i]['id_pravo'] == 3) {
                //$tplData['users'][$i]['hodnoceni'] = $this->db->getReviewsByUser($tplData['users'][$i]['id_uzivatele']);

                if (isset($tplData['users'][$i]['hodnoceni'])) {
                    for ($j = 0; $j < count($tplData['users'][$i]['hodnoceni']); $j++) {
                        if (!empty($tplData['users'][$i]['hodnoceni'][$j]['id_prispevek'])) {
                            $tplData['users'][$i]['reviews'][$j]['prispevek'] = $this->db->getArticleByID($tplData['users'][$i]['hodnoceni'][$j]['id_prispevek']);
                        }
                    }
                }
            }
        }
        */

        $tplData['prispevky'] = $this->db->getAllArticles();
        for ($i = 0; $i < count($tplData['prispevky']); $i++) {
            $tplData['prispevky'][$i]['status'] = $this->db->getStatus($tplData['prispevky'][$i]['id_status']);
            $tplData['prispevky'][$i]['autor'] = $this->db->getArticleAuthor($tplData['prispevky'][$i]['id_prispevek']);
            $tplData['prispevky'][$i]['poctyHodnoceni'] = $this->db->getCountReviews($tplData['prispevky'][$i]['id_prispevek']);

            $tplData['prispevky'][$i]['hodnoceni'] = $this->db->getArticleReviews($tplData['prispevky'][$i]['id_prispevek']);
            for ($j = 0; $j < count($tplData['prispevky'][$i]['hodnoceni']); $j++) {
                if (!empty($tplData['prispevky'][$i]['hodnoceni'][$j]['id_uzivatel'])) {
                    $tplData['prispevky'][$i]['hodnoceni'][$j]['recenzent'] =
                        $this->db->getUserNameByID($tplData['prispevky'][$i]['hodnoceni'][$j]['id_uzivatel']);
                }
            }

        }
        return $tplData;
    }


    /**
     * Funkce vrati data uvodni stranky (implementovano v potomcich - kotrollerech)
     * @param string $pageTitle Nazev stranky
     * @return array Data predana sablone
     */
    abstract public function show(string $pageTitle): array;

}