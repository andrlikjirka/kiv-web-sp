<?php

namespace kivweb_sp\models;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Obalova trida pro praci s databazi pomoci PDO
 * (vyuziti navrhoveho vzoru Singleton)
 * @author jandrlik
 */
class Database
{
    /** @var Database $dbConnection Jedina instance obalove tridy pro praci s databazi */
    private static $dbConnection;

    /** @var PDO objekt pro praci s databazi */
    private $pdo;

    /**
     * Konstruktor inicializuje pripojeni k databazi
     */
    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=" . DB_SERVER . "; dbname=" . DB_NAME . "", DB_USER, DB_PASSWORD);
            $this->pdo->exec("SET NAMES UTF8"); //vynuceni, aby data z databaze byla predana v utf8
        } catch (PDOException $e) {
            print "Error!: " . $e->getMessage() . "<br/>";
            die();
        }
    }

    /**
     * Staticka tovarni metoda
     * @return Database Vraci odkaz na jedinou instanci obalove tridy database
     */
    public static function getDBConnection(): Database
    {
        if (self::$dbConnection == null) {
            self::$dbConnection = new Database();
        }
        return self::$dbConnection;
    }

    /**
     * Funkce zajistuje odpojeni od databaze
     */
    public function disconnect()
    {
        $this->pdo = null;
    }

    //////////////////////////// ZACATEK: Konkrétní funkce ///////////////////////////////

    /**
     * Funkce ziska vsechny uzivatele z databaze
     * @return array Vrati pole uzivatelu
     */
    public function getAllUsers(): array
    {
        $q = "SELECT * FROM " . TABLE_UZIVATELE . " ORDER BY id_uzivatel";
        $res = $this->pdo->prepare($q);
        if ($res->execute()) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
        //ziskam vsechny uzivatele z tabulky uzivatelu dle ID a vratim je
        //return $this->selectFromTable(TABLE_UZIVATELE, "*", "", "id_uzivatel");
    }

    /**
     * Funkce ziska vsechny prava z databaze
     * @return array Vrati pole prav
     */
    public function getAllRightsNames(): array
    {
        $q = "SELECT nazev FROM " . TABLE_PRAVA;
        $res = $this->pdo->prepare($q);
        if ($res->execute()) {
            return $res->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce ziska vsechny clanky z databaze
     * @return array Vrati pole clanku
     */
    public function getAllArticles(): array
    {
        $q = "SELECT * FROM " . TABLE_PRISPEVKY . " ORDER BY id_status ASC, datum DESC";
        $res = $this->pdo->prepare($q);
        if ($res->execute()) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce vrati uzivatele dle zadaneho ID
     * @param int $id_uzivatel ID uzivatele
     * @return array Vrati uzivatele pokud se v db nachazi, jinak null
     */
    public function getUserByID($id_uzivatel): array
    {
        $id_uzivatel = intval($id_uzivatel);
        $q = "SELECT * FROM " . TABLE_UZIVATELE . " WHERE id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        if ($res->execute()) {
            return $res->fetch();
        } else {
            return [];
        }
    }

    /**
     * Funkce vrati jmeno a prijmeni uzivatele dle zadaneho ID
     * @param int $id_uzivatel ID uzivatele
     * @return string Vrati jmeno a prijmeni uzivatele
     */
    public function getUserNameByID($id_uzivatel): string
    {
        $id_uzivatel = intval($id_uzivatel);
        $q = "SELECT CONCAT(jmeno, ' ' , prijmeni) AS jmenoPrijmeni 
                FROM " . TABLE_UZIVATELE . " WHERE id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        if ($res->execute()) {
            return $res->fetchColumn();
        } else {
            return '';
        }
    }

    /**
     * Funkce vrati uzivatele s danym loginem
     * @param string $login Login uzivatele
     * @return array Vrati informace o uzivateli s danym loginem
     */
    public function getUserWithLogin($login): array
    {
        $login = htmlspecialchars($login);
        $q = "SELECT * FROM " . TABLE_UZIVATELE . " WHERE login=:login";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":login", $login);
        if ($res->execute()) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }

    }

    /**
     * Funkce je vyuzita pri registraci ke kontrole, zda jiz existuje v databazi nejaky uzivatel se zadanym loginem
     * @param string $login Login uzivatele
     * @return bool Vrati true, pokud uzivatel s danym loginem existuje, jinak false
     */
    public function isUserWithLogin($login): bool
    {
        $login = htmlspecialchars($login);
        $q = "SELECT * FROM " . TABLE_UZIVATELE . " WHERE login=:login";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":login", $login);

        if ($res->execute()) {
            $user = $res->fetch(PDO::FETCH_ASSOC); //vratim prvni hodnotu (udaje o jednom uzivateli)
            if (empty($user)) { //pokud nebyl v db nalezen zadny uzivatel, vraci false, pokud ano tak true
                return false;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Ziskani konkretniho prava uzivatele dle ID prava
     * @param int $id_pravo ID prava
     * @return array|null Vrati nalezene pravo pokud se v db nachazi, jinak prazdne pole
     */
    public function getRightByID($id_pravo): array
    {
        $id_pravo = intval($id_pravo);
        $q = "SELECT * FROM " . TABLE_PRAVA . " WHERE id_pravo=:id_pravo";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_pravo", $id_pravo);
        if ($res->execute()) {
            return $res->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce zjisti nazev prava uzivatele dle zadaneho ID uzivatele
     * @param int $id_uzivatel ID uzivatele
     * @return string Nazev prava uzivatele
     */
    public function getUserRight($id_uzivatel): string
    {
        $id_uzivatel = intval($id_uzivatel);
        $q = "SELECT p.nazev FROM " . TABLE_UZIVATELE . " u, " . TABLE_PRAVA . " p WHERE u.id_pravo = p.id_pravo AND u.id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        if ($res->execute()) return $res->fetchColumn();
        else return '';
    }

    /**
     * Funkce zjisti vsechny informace o prispevku dle zadaneho ID
     * @param int $id_prispevek ID prispevku
     * @return array Vrati vsechny informace o clanku, pokud existuje, jinak prazdne pole
     */
    public function getArticleByID($id_prispevek): array
    {
        $id_prispevek = intval($id_prispevek);
        $q = "SELECT * FROM " . TABLE_PRISPEVKY . " WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue("id_prispevek", $id_prispevek);
        if ($res->execute()) {
            return $res->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce zjisti ID a jmenoPrijmeni autora clanku dle zadaneho ID clanku
     * @param int $id_prispevek ID prispevku
     * @return array Vrati info o autorovi clanku pokud existuje, jinak prazdne pole
     */
    public function getArticleAuthor($id_prispevek): array
    {
        $id_prispevek = intval($id_prispevek);
        //$q = "SELECT users.id_uzivatel, CONCAT(jmeno , ' ' , prijmeni) AS jmenoPrijmeni
        //FROM " . TABLE_UZIVATELE . " users JOIN " . TABLE_PRISPEVKY . " articles ON users.id_uzivatel = articles.id_uzivatel WHERE id_prispevek=:id_prispevek";
        $q = "SELECT u.id_uzivatel, CONCAT(u.jmeno, ' ' , u.prijmeni) AS jmenoPrijmeni 
                FROM " . TABLE_UZIVATELE . " u, " . TABLE_PRISPEVKY . " p 
                WHERE id_prispevek = :id_prispevek
                AND u.id_uzivatel = p.id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_prispevek", $id_prispevek);
        if ($res->execute()) {
            return $res->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce zjisti vsechny prirazen recenze dle daneho ID prispevku
     * @param int $id_prispevek ID prispevku
     * @return array Vrati pole recenzi daneho prispevku pokud existuji, jinak prazdne pole
     */
    public function getArticleReviews($id_prispevek): array
    {
        $id_prispevek = intval($id_prispevek);
        $q = "SELECT * FROM " . TABLE_HODNOCENI . " WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_prispevek", $id_prispevek);
        if ($res->execute()) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce prida noveho uzivatele do databaze
     * @param string $login Login uzivatele
     * @param string $heslo Heslo uzivatele
     * @param string $jmeno Jmeno uzivatele
     * @param string $email Email uzivatele
     * @param int $id_pravo ID prava uzivatele
     * @param int $povolen Uzivatel povolen nebo nepovolen
     * @return bool Vrati true pokud je pridani uzivatele probehne uspesne, jinak false
     */
    public function addNewUser(string $jmeno, string $prijmeni, string $login, string $heslo, string $email, int $id_pravo = 4, int $povolen = 1): bool
    {
        $jmeno = htmlspecialchars($jmeno);
        $prijmeni = htmlspecialchars($prijmeni);
        $login = htmlspecialchars($login);
        $email = htmlspecialchars($email);
        $id_pravo = intval($id_pravo);
        $povolen = intval($povolen);

        $q = "INSERT INTO " . TABLE_UZIVATELE . " (jmeno, prijmeni, login, heslo, email, id_pravo, povolen)
              VALUES (:jmeno, :prijmeni, :login, :heslo, :email, :id_pravo, :povolen)";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":jmeno", $jmeno);
        $res->bindValue(":prijmeni", $prijmeni);
        $res->bindValue(":login", $login);
        $res->bindValue(":heslo", $heslo);
        $res->bindValue(":email", $email);
        $res->bindValue(":id_pravo", $id_pravo);
        $res->bindValue(":povolen", $povolen);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funkce smaze uzivatele s danym ID z databaze
     * @param int $id_user ID uzivatele
     * @return bool Vrati true, pokud smazani uzivatele probehne uspesne, jinak false
     */
    public function deleteUser($id_uzivatel): bool
    {
        $id_uzivatel = intval($id_uzivatel);
        $q = "DELETE FROM " . TABLE_UZIVATELE . " WHERE id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funkce upravi pravo uzivatele
     * @param int $id_uzivatel ID uzivatele
     * @param int $id_pravo Pravo uzivatele
     * @return bool Vrati true pokud uprava prava uzivatele probehne uspesne, jinak false
     */
    public function updateUserRight(int $id_uzivatel, int $id_pravo): bool
    {
        $id_uzivatel = intval($id_uzivatel);
        $id_pravo = intval($id_pravo);
        $q = "UPDATE " . TABLE_UZIVATELE . " SET id_pravo=:id_pravo WHERE id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        $res->bindValue(":id_pravo", $id_pravo);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funkce blokuje nebo povoluje uzivatele
     * @param int $id_uzivatel ID uzivatele
     * @param int $povolen Hodnota, zda ma byt uzivatel povolen (1) nebo zablokovan (0)
     * @return bool Vrati true pokud je akce uspesna, jinak false
     */
    public function updateBlockAllowUser(int $id_uzivatel, int $povolen): bool
    {
        $id_uzivatel = intval($id_uzivatel);
        $povolen = intval($povolen);
        $q = "UPDATE " . TABLE_UZIVATELE . " SET povolen=:povolen WHERE id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        $res->bindValue(":povolen", $povolen);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * @param string $nadpis Nadpis clanku
     * @param string $abstrakt Abstrakt clanku
     * @param string $dokument Nazev nahraneho souboru pdf
     * @param mixed $datum Datum nahrani souboru
     * @param int $id_uzivatel ID autora
     * @param string $id_status ID statustu prispevku
     * @return bool Vrati true, pokud je pridani prispevku uspesne, jinak false
     */
    public function addNewArticle($nadpis, $abstrakt, $dokument, $datum, $id_uzivatel, $id_status = '1'): bool
    {
        $nadpis = htmlspecialchars($nadpis);
        $abstrakt = htmlspecialchars($abstrakt);
        $dokument = htmlspecialchars($dokument);
        $id_uzivatel = intval($id_uzivatel);
        $id_status = intval($id_status);
        $q = "INSERT INTO " . TABLE_PRISPEVKY . "(nadpis, abstrakt, dokument, datum, id_uzivatel, id_status)
              VALUES (:nadpis, :abstrakt, :dokument, :datum, :id_uzivatel, :id_status)";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":nadpis", $nadpis);
        $res->bindValue(":abstrakt", $abstrakt);
        $res->bindValue(":dokument", $dokument);
        $res->bindValue(":datum", $datum);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        $res->bindValue(":id_status", $id_status);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funkce ziska vsechny autorovy clanky dle zadaneho ID autora
     * @param int $id_uzivatel ID uzivatele
     * @return array Vrati pole clanku autora pokud existuji, jinak prazdne pole
     */
    public function getArticlesByUser($id_uzivatel): array
    {
        $id_uzivatel = intval($id_uzivatel);
        $q = "SELECT * FROM " . TABLE_PRISPEVKY . " WHERE id_uzivatel=:id_uzivatel ORDER BY id_status";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        $articles = $res->execute();
        if (!empty($articles)) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce odstrani prispevek dle zadaneho ID prispevku
     * @param int $id_prispevek ID prispevku
     * @return bool Vrati true pokud bylo odstraneni clanku uspesne, jinak false
     */
    public function deleteArticle($id_prispevek): bool
    {
        $id_prispevek = intval($id_prispevek);
        $q = "DELETE FROM " . TABLE_PRISPEVKY . " WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_prispevek", $id_prispevek);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getPublishedArticles()
    {
        $q = "
        SELECT p.nadpis, p.abstrakt, p.datum, p.dokument, CONCAT(u.jmeno, ' ', u.prijmeni) as autor
        FROM jandrlik_prispevky p,
             jandrlik_uzivatele u
        WHERE p.id_uzivatel = u.id_uzivatel
        AND   p.id_status = 2
        ORDER BY p.datum DESC";
        $res = $this->pdo->prepare($q);
        if ($res->execute()) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * @param int $id_prispevek ID prispevku
     * @param string $nadpis Opraveny nadpis clanku
     * @param string $abstrakt Opraveny abstrakt
     * @param string $dokument Opraveny nahrany soubor
     * @param string $datum Datum nahrani opraveneho souboru
     * @return bool Vrati true pokud byla uprava clanku uspesna, jinak false
     */
    public function updateArticle($id_prispevek, $nadpis, $abstrakt, $dokument = "", $datum = ""): bool
    {
        $id_prispevek = intval($id_prispevek);
        $nadpis = htmlspecialchars($nadpis);
        $abstrakt = htmlspecialchars($abstrakt);

        if (empty($dokument) || empty($datum)) {
            $q = "UPDATE " . TABLE_PRISPEVKY . " SET nadpis=:nadpis, abstrakt=:abstrakt WHERE id_prispevek=:id_prispevek";
            $res = $this->pdo->prepare($q);
            $res->bindValue(":nadpis", $nadpis);
            $res->bindValue(":abstrakt", $abstrakt);
            $res->bindValue(":id_prispevek", $id_prispevek);

        } else {
            $q = "UPDATE " . TABLE_PRISPEVKY . " SET nadpis=:nadpis, abstrakt=:abstrakt, dokument=:dokument, datum=:datum WHERE id_prispevek=:id_prispevek";
            $res = $this->pdo->prepare($q);
            $res->bindValue(":nadpis", $nadpis);
            $res->bindValue(":abstrakt", $abstrakt);
            $res->bindValue(":id_prispevek", $id_prispevek);
            $res->bindValue(":dokument", $dokument);
            $res->bindValue(":datum", $datum);
        }
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }

    }

    /**
     * Funkce zjisti recenze recenzenta dle zadaneho ID
     * @param int $id_uzivatel ID recenzenta
     * @return array Vrati vsechny recenze recenzenta pokud existuji, jinak prazdne pole
     */
    public function getReviewsByUser($id_uzivatel): array
    {
        $id_uzivatel = intval($id_uzivatel);
        $q = "SELECT h.id_hodnoceni, h.obsah, h.odbornost, h.jazyk, p.id_prispevek, s.id_status, s.nazev as status, p.nadpis, p.abstrakt, p.dokument, p.datum, CONCAT(u.jmeno, ' ', u.prijmeni) as autor
                FROM jandrlik_prispevky p,
                     jandrlik_hodnoceni h,
                     jandrlik_uzivatele u,
                     jandrlik_status s
                WHERE h.id_uzivatel = :id_recenzent
                AND   u.id_uzivatel = p.id_uzivatel
                AND   p.id_status = s.id_status
                AND   h.id_prispevek = p.id_prispevek
                ORDER BY s.id_status ASC, p.datum DESC";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_recenzent", $id_uzivatel);
        if ($res->execute()) {
            return $res->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    /**
     * Funkce prida do tabulky recenzi novy zaznam recenze
     * @param int $id_recenzent ID recenzenta
     * @param int $id_prispevek ID recenzovaneho prispevku
     * @return bool Vrati true pokud byla akce uspesna, jinak false
     */
    public function addNewReview($id_recenzent, $id_prispevek): bool
    {
        $id_recenzent = intval($id_recenzent);
        $id_prispevek = intval($id_prispevek);
        $q = "INSERT INTO " . TABLE_HODNOCENI . "(id_uzivatel, id_prispevek) VALUES (:id_recenzent, :id_prispevek)";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_recenzent", $id_recenzent);
        $res->bindParam(":id_prispevek", $id_prispevek);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funkce odstrani recenzi prispevku dle zadaneho ID recenze
     * @param int $id_hodnoceni ID recenze
     * @return bool Vrati true pokud byla akce uspesna, jinak false
     */
    public function deleteReview($id_hodnoceni): bool
    {
        $id_hodnoceni = intval($id_hodnoceni);
        $q = "DELETE FROM " . TABLE_HODNOCENI . " WHERE id_hodnoceni = :id_hodnoceni";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_hodnoceni", $id_hodnoceni);
        if ($res->execute()) return true;
        else return false;
    }

    /**
     * @param int $id_hodnoceni ID hodnoceni
     * @param int $obsah Hodnoceni obsahu clanku
     * @param int $jazyk Hodnoceni jazyka clanku
     * @param int $odbornost Hodnoceni odbornosti clanku
     * @return bool Vrati true pokud byla akce uspesna, jinak false
     */
    public function updateReview($id_hodnoceni, $obsah, $jazyk, $odbornost): bool
    {
        $id_hodnoceni = intval($id_hodnoceni);
        $obsah = intval($obsah);
        $jazyk = intval($jazyk);
        $odbornost = intval($odbornost);
        $q = "UPDATE " . TABLE_HODNOCENI . " SET obsah=:obsah, jazyk=:jazyk, odbornost=:odbornost WHERE id_hodnoceni=:id_hodnoceni";
        //provedu dotaz a vratim vysledek
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_hodnoceni", $id_hodnoceni);
        $res->bindValue(":obsah", $obsah);
        $res->bindValue(":jazyk", $jazyk);
        $res->bindValue(":odbornost", $odbornost);
        if ($res->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Funkce ziska nazev statusu dle zadaneho ID statusu
     * @param int $id_status ID statusu
     * @return string Vrati nazev statusu nebo prazdny retezec
     */
    public function getStatus($id_status): string
    {
        $id_status = intval($id_status);
        $q = "SELECT nazev FROM " . TABLE_STATUS . " WHERE id_status=:id_status";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_status", $id_status);
        if ($res->execute()) return $res->fetchColumn();
        else return '';
    }

    /**
     * Funkce zmeni status clanku prispevku
     * @param int $id_prispevek ID prispevku
     * @param int $id_status ID noveho statusu
     * @return bool Vrati true pokud byla akce uspesna, jinak false
     */
    public function changeArticleStatus($id_prispevek, $id_status): bool
    {
        $id_prispevek = intval($id_prispevek);
        $id_status = intval($id_status);
        $q = "UPDATE " . TABLE_PRISPEVKY . " SET id_status=:id_status WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_status", $id_status);
        $res->bindValue(":id_prispevek", $id_prispevek);
        if ($res->execute()) return true;
        else return false;
    }

    /**
     * Funkce vrati pocty vyplnenych hodnoceni jednotlivych kategorii u prispevku
     * @param int $id_prispevek ID prispevku
     * @return array Pole poctu hodnoceni jednotlivych kategorii, pokud existuji, jinak false
     */
    public function getCountReviews($id_prispevek): array
    {
        $id_prispevek = intval($id_prispevek);
        $q = "SELECT COUNT(obsah) as countObsah, COUNT(jazyk) as countJazyk, COUNT(odbornost) as countOdbornost
                FROM jandrlik_hodnoceni
                WHERE id_prispevek = :id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_prispevek", $id_prispevek);
        if ($res->execute()) {
            return $res->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    //////////////////////////////// KONEC: Konkretni funkce ///////////////////////////////////////


}