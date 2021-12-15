<?php

namespace kivweb_sp\controllers;

use kivweb_sp\models\Database;
use kivweb_sp\models\Login;
use kivweb_sp\models\Registration;

/**
 * Abstraktni kontroler, predek konkretnich kontrolleru
 * @author jandrlik
 */
abstract class AController implements IController
{

    /** @var Database $db Objekt pripojeni k databazi */
    protected $db;
    /** @var Login $login Objekt pro spravu prihlaseni */
    protected $login;
    /** @var Registration $registration Objekt pro spravu registrace */
    protected $registration;

    /**
     * Konstruktor slouzi pro inicializaci tridy
     * Pri inicializaci tridy je ziskan objekt pro pripojeni k databazi, objekt spravy prihlaseni a registrace
     */
    public function __construct()
    {
        $this->db = Database::getDBConnection();
        $this->login = new Login();
        $this->registration = new Registration();
    }

    /**
     * Funkce spravuje prihlasovaci formular
     */
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
                    //echo "OK: Uživatel byl přihlášen.";
                } else {
                    //echo "ERROR: Přihlášení uživatele se nezdařilo";
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Nesprávné jméno nebo heslo.</div>";
                }
            }
        }
    }

    /**
     * Funkce spravuje formular odhlaseni
     */
    protected function handleLogoutForm()
    {
        if (isset($_POST['action'])) {
            //odhlaseni
            if ($_POST['action'] == 'logout') {
                //odhlasim uzivatele
                $this->login->logout();
                //echo "OK: Uživatel byl odhlášen.";
            } //neznama akce
            else {
                echo "WARNING: Neznama akce.";
            }
        }
    }

    /**
     * Funkce spravuje registracni formular
     */
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
                    $result = $this->registration->registrateUser($_POST['jmeno'], $_POST['prijmeni'], $_POST['login'], $_POST['password1'], $_POST['email']);
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

    /**
     * Funkce spravuje formular mazani uzivatelu
     */
    protected function handleDeleteUserForm()
    {
        // zpracovani odeslaneho formulare pro odstraneni uzivatele
        if (isset($_POST['smazat_id_uzivatel'])) {
            //nejprve musim ziskat clanky uzivatele, projit nazvy souboru a smazat je ze serveru
            $articles = $this->db->getArticlesByUser($_POST['smazat_id_uzivatel']);
            if (!empty($articles)) {
                foreach ($articles as $article) {
                    $articleName = $article['dokument'];
                    $UPLOADS_DIR = getcwd() . DIRECTORY_SEPARATOR . "uploads\\";
                    $dokument = $UPLOADS_DIR. basename($articleName . ".pdf");
                    unlink($dokument);
                }
            }

            //smazu daneho uzivatele z databaze
            $res = $this->db->deleteUser($_POST['smazat_id_uzivatel']);
            //vysledek maazani
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Smazání uživatele s ID: $_POST[smazat_id_uzivatel] proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Smazání uživatele s ID: $_POST[smazat_id_uzivatel] se nezdařilo.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular zmeny role uzivatelu
     */
    protected function handleChangeRoleForm()
    {
        // zpracovani odeslaneho formulare pro odstraneni uzivatele
        if (isset($_POST['zmena_prava_id_uzivatel']) && isset($_POST['pravo'])) {
            $res = $this->db->updateUserRight($_POST['zmena_prava_id_uzivatel'], $_POST['pravo']);
            if ($res == null) {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Změna práva uživatele s ID: $_POST[zmena_prava_id_uzivatel] proběhla neúspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Změna práva uživatele s ID: $_POST[zmena_prava_id_uzivatel] proběhla úspěšně.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular blokovani a povoleni uzivatelu
     */
    protected function handleBlockAllowUserForm()
    {
        if (isset($_POST['stav_id_uzivatel'])) {
            $res = $this->db->updateBlockAllowUser($_POST['stav_id_uzivatel'], $_POST['povolen']);
            if ($res == false) { //update se neprovedl
                if ($_POST['povolen'] == 0) {
                    //pozadavek na povolen=0, neprovedl se update => uzivatel nezablokovan
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Zablokování uživatele s ID: $_POST[stav_id_uzivatel] proběhlo neúspěšně.</div>";
                } else if ($_POST['povolen'] == 1) {
                    //pozadavek na povolen=1, neprovedl se update => uzivatel nepovolen
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Povolení uživatele s ID: $_POST[stav_id_uzivatel] proběhlo neúspěšně.</div>";
                }
            } else {
                if ($_POST['povolen'] == 0) {
                    //pozadavek na povolen=0, provedl se update => uzivatel zablokovan
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Zablokování uživatele s ID: $_POST[stav_id_uzivatel] proběhlo úspěšně.</div>";
                } else if ($_POST['povolen'] == 1){
                    //pozadavek na povolen=1, provedl se update => uzivatel povolen
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Povolení uživatele s ID: $_POST[stav_id_uzivatel] proběhlo úspěšně.</div>";
                }
            }
        }
    }

    /**
     * Funkce spravuje formular pridani noveho prispevku
     */
    protected function handleNewArticleForm()
    {
        $UPLOADS_DIR = getcwd() . DIRECTORY_SEPARATOR . "uploads\\";

        if (isset($_POST['action']) && $_POST['action'] == 'new-article'
            && $_POST['nazev-clanku'] != "" && !empty($_POST['user_id']) && $_POST['abstrakt'] != "" && isset($_FILES['uploadFile']['type'])
        ) {
            if ($_FILES['uploadFile']['type'] == 'application/pdf') {
                //nahran typ souboru PDF
                $uploadDate = date("Y-m-d G:i:s");
                $uploadDateTime = date("Y-m-d") . "_" . time();
                $articleName = $_POST['user_id'] . "_" . $uploadDateTime;
                $target = $UPLOADS_DIR . basename($articleName . ".pdf");

                $res = $this->db->addNewArticle($_POST['nazev-clanku'], $_POST['abstrakt'], $articleName, $uploadDate, $_POST['user_id']);
                if ($res) {
                    move_uploaded_file($_FILES['uploadFile']['tmp_name'], $target);
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Přidání příspěvku proběhlo úspěšně.</div>";
                } else {
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Přidání příspěvku se nezdařilo.</div>";
                }
            } else {
                //nahran jiny typ souboru
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Přidání příspěvku se nezdařilo. Nahrajte soubor ve formátu PDF.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular upravy prispevku
     */
    protected function handleEditArticleForm()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'edit-article'
            && $_POST['nazev-clanku'] != "" && !empty($_POST['article_id']) && $_POST['abstrakt'] != ""
        ) {
            if (isset($_FILES['editUploadFile']['type']) && $_FILES['editUploadFile']['type'] == 'application/pdf') {
                //upraveny soubor nahran

                //stary soubor (bude odstranen)
                $oldArticle = $this->db->getArticleByID($_POST['article_id']);
                $oldArticleName = $oldArticle['dokument'];
                $UPLOADS_DIR = getcwd() . DIRECTORY_SEPARATOR . "uploads\\";
                $oldFile = $UPLOADS_DIR. basename($oldArticleName . ".pdf");

                //novy soubor
                $uploadDate = date("Y-m-d G:i:s");
                $uploadDateTime = date("Y-m-d") . "_" . time();
                $articleName = $_POST['user_id'] . "_" . $uploadDateTime;
                $target = $UPLOADS_DIR . basename($articleName . ".pdf");

                $res = $this->db->updateArticle($_POST['article_id'], $_POST['nazev-clanku'], $_POST['abstrakt'], $articleName, $uploadDate);
                if ($res) {
                    move_uploaded_file($_FILES['editUploadFile']['tmp_name'], $target);
                    unlink($oldFile);
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Úprava příspěvku s ID:$_POST[article_id] se souborem proběhla úspěšně.</div>";
                } else {
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Úprava příspěvku s ID:$_POST[article_id] se nezdařila.</div>";
                }

            } else if ($_FILES['editUploadFile']['type'] && $_FILES['editUploadFile']['type'] != 'application/pdf') {
                //nahrany soubor neni ve formatu pdf
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Úprava příspěvku s ID:$_POST[article_id] se nezdařila. Nahrajte soubor ve formátu PDF.</div>";
            } else {
                //upraveny soubor nenahran
                $res = $this->db->updateArticle($_POST['article_id'], $_POST['nazev-clanku'], $_POST['abstrakt']);
                if ($res) {
                    echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Úprava příspěvku s ID:$_POST[article_id] proběhla úspěšně.</div>";
                } else {
                    echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Úprava příspěvku s ID:$_POST[article_id] se nezdařila.</div>";
                }
            }
        }
    }

    /**
     *
     * Odstrani se i pdf soubor prirazeny k danemu clanku
     * Kvuli nastavenemu zachovani integrity ON DELETE CASCADE, se provede automaticke smazani i hodnoceni prirazenych k prispevku, ktery se maze
     */
    protected function handleDeleteArticleForm()
    {
        if (isset($_POST['smazat_id_clanek'])) {
            //nejprve ziskam nazev dokumentu
            $article = $this->db->getArticleByID($_POST['smazat_id_clanek']);
            $articleName = $article['dokument'];
            $UPLOADS_DIR = getcwd() . DIRECTORY_SEPARATOR . "uploads\\";
            $dokument = $UPLOADS_DIR. basename($articleName . ".pdf");

            $res = $this->db->deleteArticle($_POST['smazat_id_clanek']);
            if ($res) {
                //odstraneni dokumentu ze serveru, pokud bylo samzani clanku z databaze uspesne
                unlink($dokument);
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Smazání příspěvku s ID: $_POST[smazat_id_clanek] proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Smazání příspěvku s ID: $_POST[smazat_id_clanek] se nezdařilo.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular pridani recenzenta k prispevku
     */
    protected function handleAddReviewerForm()
    {
        if (isset($_POST['priradit_recenzenta_id_uzivatel']) && isset($_POST['priradit_recenzenta_id_prispevek'])) {
            //priradim recenezenta
            $res = $this->db->addNewReview($_POST['priradit_recenzenta_id_uzivatel'], $_POST['priradit_recenzenta_id_prispevek']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Přiřazení recenzenta proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Přiřazení recenzenta se nezdařilo.</div>";
            }

        }
    }

    /**
     * Funkce spravuje formular odebrani recenze (recenzenta) z prispevku
     */
    protected function handleDeleteReviewerForm()
    {
        if (isset($_POST['smazat_id_hodnoceni'])) {
            $res = $this->db->deleteReview($_POST['smazat_id_hodnoceni']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Odstranění recenzenta proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Odstranění recenzenta se nezdařilo.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular upravy recenze prispevku
     */
    protected function handleUpdateReviewForm()
    {
        if (isset($_POST['action']) && $_POST['action'] == "ulozit-recenzi") {
            $res = $this->db->updateReview($_POST['id_hodnoceni'], $_POST['obsah'], $_POST['jazyk'], $_POST['odbornost']);

            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Recenzování příspěvku proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-danger text-center mt-5' role='alert'>Recenzování příspěvku se nezdařilo.</div>";

            }


        }
    }

    /**
     * Funkce spravuje formular schvaleni prispevku
     */
    protected function handleApproveArticleForm()
    {
        if (isset($_POST['schvalit_id_clanek']) && isset($_POST['schvalit_id_status'])) {
            $res = $this->db->changeArticleStatus($_POST['schvalit_id_clanek'], $_POST['schvalit_id_status']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Schválení článku s ID: $_POST[schvalit_id_clanek] proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Schválení článku s ID: $_POST[schvalit_id_clanek] proběhlo neúspěšně.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular zamitnuti prispevku
     */
    protected function handleRejectArticleForm()
    {
        if (isset($_POST['zamitnout_id_clanek']) && isset($_POST['zamitnout_id_status'])) {
            $res = $this->db->changeArticleStatus($_POST['zamitnout_id_clanek'], $_POST['zamitnout_id_status']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Zamítnutí článku s ID: $_POST[zamitnout_id_clanek] proběhlo úspěšně.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Zamítnutí článku s ID: $_POST[zamitnout_id_clanek] proběhlo neúspěšně.</div>";
            }
        }
    }

    /**
     * Funkce spravuje formular znovu posouzeni prispevku
     */
    public function handleReviewAgainForm()
    {
        if (isset($_POST['znovu_posoudit_id_clanek']) && isset($_POST['znovu_posoudit_id_status'])) {
            $res = $this->db->changeArticleStatus($_POST['znovu_posoudit_id_clanek'], $_POST['znovu_posoudit_id_status']);
            if ($res) {
                echo "<br><br><div class='alert alert-success text-center mt-5' role='alert'>Článek s ID: $_POST[znovu_posoudit_id_clanek] je znovu možné posoudit.</div>";
            } else {
                echo "<br><br><div class='alert alert-warning text-center mt-5' role='alert'>Článek s ID: $_POST[znovu_posoudit_id_clanek] není znovu možné posoudit.</div>";
            }
        }
    }

    /**
     * Funkce ziskava vsechna potrebna data, ktere predava sablonam
     * @return array Pole vsech potrebnych dat
     */
    protected function getData():array
    {
        global $tplData;
        $tplData = [];

        if ($this->login->isUserLoggedIn()) {
            $tplData['isUserLoggedIn'] = true;
            $tplData['userData'] = $this->login->getLoggedUserData();
            $tplData['userData']['nazevPravo'] = $this->db->getUserRight($tplData['userData']['id_uzivatel']);
            if ($tplData['userData']['id_pravo'] == PRAVO_AUTOR) { //prihlaseny uzivatel je autor
                $tplData['userArticles'] = $this->db->getArticlesByUser($tplData['userData']['id_uzivatel']);
            }
            if ($tplData['userData']['id_pravo'] == PRAVO_RECENZENT) { //prihlaseny uzivatel je recenzent
                $tplData['userReviews'] = $this->db->getReviewsByUser($tplData['userData']['id_uzivatel']);
            }
            //if prihlaseny uzivatel je alespon admin tak getAllUsers, articles ???
            $tplData['UPLOADS_DIR'] = ".\\uploads\\";

        } else {
            $tplData['isUserLoggedIn'] = false;
        }

        $tplData['allUsers'] = $this->db->getAllUsers(); //POZOR: na ziskavani vsech data vcetne hesel

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
     * Funkce predava pole dat ziskane z modelu do view a zaroven zpracovava potrebne formulare
     * @param string $pageTitle Nazev stranky
     * @return array Data predana sablone
     */
    abstract public function show(string $pageTitle): array;

}