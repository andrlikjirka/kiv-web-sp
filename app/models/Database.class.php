<?php

namespace kivweb_sp\models;

use PDO;
use PDOException;
use PDOStatement;

/**
 * Obalova trida pro praci s databazi pomoci PDO
 * (vyuziti navrhoveho vzoru Singleton)
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

    ///////////////////////////////// ZACATEK: Obecne funckce /////////////////////////////////////////

    /**
     * Provede dotaz a bud vrati ziskana data, nebo pri chybe ji vypise a vrati null
     * @param string $query SQL dotaz
     * @return false|PDOStatement|null Vysledek dotazu
     */
    private function executeQuery(string $query)
    {
        try {
            //vykonam dotaz
            $result = $this->pdo->query($query);
            return $result;
        } catch (PDOException $e) {
            //vypisu prislusnou chybu a vratim null
            echo $e->getMessage();
            return null;
        }
    }

    /**
     * Genericky dotaz pro vyber z tabulky
     * @param string $columnsNames Nazev sloupcu
     * @param string $whereStatement Pripadne omezeni na ziskani radek tabulky, default=""
     * @param string $orderByStatement Pripadne razeni ziskanych radek tabulky, default=""
     * @return array Vraci pole hodnot z db
     */
    public function selectFromTable($tableName, $columnsNames = "*", $whereStatement = "", $orderByStatement = ""): array
    {
        //slozim dotaz
        $query = "SELECT $columnsNames FROM $tableName"
            . ((empty($whereStatement)) ? "" : " WHERE $whereStatement")
            . ((empty($orderByStatement)) ? "" : " ORDER BY $orderByStatement");
        //provedu ho a vratim vysledek
        $result = $this->executeQuery($query);
        //pokud je null, tak vratim prazdne pole
        if ($result == null) {
            return [];
        }
        //prevedu vsechny ziskane radky tabulky na pole
        return $result->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Funkce dle zadane podminky maze radky v prislusne podmince
     * @param string $tableName Nazev tabulky
     * @param string $whereStatement Podminka mazani
     * @return bool Vrati true pokud smazani probehlo uspesne, jinak false
     */
    private function deleteFromTable(string $tableName, string $whereStatement)
    {
        //slozim dotaz
        $query = "DELETE FROM $tableName WHERE $whereStatement";
        //provedu ho a vratim vysledek
        $result = $this->executeQuery($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Funkce vklada hodnoty do databaze
     * @param string $tableName Nazev tabulky
     * @param string $insertStatement Podminka vkladani
     * @param string $insertValues Vkladane hodnoty
     * @return bool Vrati true pokud vlozeni hodnot probehlo uspesne, jinak false
     */
    private function insertIntoTable(string $tableName, string $insertStatement, string $insertValues): bool
    {
        //slozim dotaz
        $query = "INSERT INTO $tableName($insertStatement) VALUES ($insertValues)";
        $result = $this->executeQuery($query);
        if ($result == null) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Funkce upravuje hodnoty zaznamu v tabulce
     * @param string $tableName Nazev tabulky
     * @param string $updateStatementWithValues Upravovane hodnoty
     * @param string $whereStatement Podminka
     * @return bool Vrati true pokud update probehl uspesne, jinak false
     */
    private function updateInTable(string $tableName, string $updateStatementWithValues, string $whereStatement): bool
    {
        //slozim dotaz
        $query = "UPDATE $tableName SET $updateStatementWithValues WHERE $whereStatement";
        //provedu a vratim vysledek
        $obj = $this->executeQuery($query);
        if ($obj == null) {
            return false;
        } else {
            return true;
        }
    }

    ///////////////////////////// KONEC: Obecné funkce ///////////////////////////////////

    //////////////////////////// ZACATEK: Konkrétní funkce ///////////////////////////////

    /**
     * Funkce ziska vsechny uzivatele z databaze
     * @return array Vrati pole uzivatelu
     */
    public function getAllUsers()
    {
        //ziskam vsechny uzivatele z tabulky uzivatelu dle ID a vratim je
        return $this->selectFromTable(TABLE_UZIVATELE, "*", "", "id_uzivatel");
    }

    /**
     * Funkce ziska vsechny prava z databaze
     * @return array Vrati pole prav
     */
    public function getAllRights()
    {
        return $this->selectFromTable(TABLE_PRAVA);
    }

    /**
     * Funkce ziska vsechny clanky z databaze
     * @return array Vrati pole clanku
     */
    public function getAllArticles()
    {
        $q = "SELECT * FROM " . TABLE_PRISPEVKY . " ORDER BY id_status ASC, datum ASC";

        $res = $this->pdo->prepare($q);
        if ($res->execute()) return $res->fetchAll(PDO::FETCH_ASSOC);
        else return [];
    }

    /*
     * Funkce vrati vsechna hodnoceni z databaze
     * @return array Vrati pole hodnoceni
     */
    public function getAllReviews()
    {
        return $this->selectFromTable(TABLE_HODNOCENI);
    }

    /**
     * Funkce vrati uzivatele dle zadaneho ID
     * @param int $id_uzivatel ID uzivatele
     * @return mixed|null Vrati uzivatele pokud se v db nachazi, jinak null
     */
    public function getUserByID($id_uzivatel)
    {
        $user = $this->selectFromTable(TABLE_UZIVATELE, "*", "id_uzivatel='$id_uzivatel'", "");
        //mam pole s jednou hodnotou
        if (empty($user)) {
            return null;
        } else {
            return $user[0]; //vratim prvni hodnotu (udaje o jednom uzivateli)
        }
    }

    public function getUserNameByID($id_uzivatel)
    {
        $q = "SELECT CONCAT(jmeno, ' ' , prijmeni) AS jmenoPrijmeni 
                FROM " . TABLE_UZIVATELE . " WHERE id_uzivatel = $id_uzivatel";
        $result = $this->executeQuery($q);
        if ($result == null) {
            return [];
        }
        //prevedu vsechny ziskane radky tabulky na pole
        return $result->fetchColumn();
    }

    public function isUserWithLogin($login)
    {
        $user = $this->selectFromTable(TABLE_UZIVATELE, "*", "login='$login'", "");
        if (empty($user)) {
            return false;
        } else {
            return true;
        }
    }

    /**
     * Ziskani konkretniho prava uzivatele dle ID prava
     * @param int $id ID prava
     * @return array|null Vrati nalezene pravo pokud se v db nachazi, jinak null
     */
    public function getRightByID($id)
    {
        //ziskam pravo dle ID
        $right = $this->selectFromTable(TABLE_PRAVA, "*", "id_pravo='$id'");
        if (empty($right)) {
            return null;
        } else {
            //vracim nalezene pravo (prvni v poli prav ktere mi vraci select)
            return $right[0];
        }
    }

    public function getUserRight($id_uzivatel)
    {
        $q = "SELECT p.nazev FROM " . TABLE_UZIVATELE . " u, " . TABLE_PRAVA . " p WHERE u.id_pravo = p.id_pravo AND u.id_uzivatel=:id_uzivatel";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_uzivatel", $id_uzivatel);
        if ($res->execute()) return $res->fetchColumn();
        else return '';
    }

    public function getArticleByID($id)
    {
        //ziskam clanek dle ID
        $article = $this->selectFromTable(TABLE_PRISPEVKY, "*", "id_prispevek='$id'");
        if (empty($article)) {
            return null;
        } else {
            return $article[0];
        }
    }

    //POZOR
    public function getArticleAuthor($id_prispevek)
    {
        //$q = "SELECT users.id_uzivatel, CONCAT(jmeno , ' ' , prijmeni) AS jmenoPrijmeni
        //FROM " . TABLE_UZIVATELE . " users JOIN " . TABLE_PRISPEVKY . " articles ON users.id_uzivatel = articles.id_uzivatel WHERE id_prispevek=:id_prispevek";
        $q = "SELECT u.id_uzivatel, CONCAT(u.jmeno, ' ' , u.prijmeni) AS jmenoPrijmeni 
                FROM " . TABLE_UZIVATELE." u, ". TABLE_PRISPEVKY . " p 
                WHERE id_prispevek = :id_prispevek
                AND u.id_uzivatel = p.id_uzivatel";

        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_prispevek", $id_prispevek);

        if ($res->execute()) return $res->fetch(PDO::FETCH_ASSOC);
        else return '';
    }

    //POZOR
    public function getArticleReviews($id_prispevek)
    {
        $q = "SELECT * FROM " . TABLE_HODNOCENI . " WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_prispevek", $id_prispevek);

        if ($res->execute()) return $res->fetchAll();
        else return [];
    }

    public function getReviewID($id)
    {
        //ziskam hodnoceni dle ID
        $review = $this->selectFromTable(TABLE_HODNOCENI, "*", "id_hodnoceni='$id'");
        if (empty($review)) {
            return null;
        } else {
            return $review[0];
        }
    }

    /**
     * Funkce prida noveho uzivatele do databaze
     * @param string $login Login uzivatele
     * @param string $heslo Heslo uzivatele
     * @param string $jmeno Jmeno uzivatele
     * @param string $email Email uzivatele
     * @param int $id_pravo ID prava uzivatele
     * @return bool Vrati true pokud je pridani uzivatele probehne uspesne, jinak false
     */
    public function addNewUser(string $jmeno, string $prijmeni, string $login, string $heslo, string $email, int $id_pravo = 4, int $povolen = 1)
    {
        //hlavicka pro vlozeni do tabulky uzivatelu
        $insertStatements = "jmeno, prijmeni, login, heslo, email, id_pravo, povolen";
        //hodnoty pro vlozeni do tabulky uzivatelu
        $insetValues = "'$jmeno', '$prijmeni', '$login', '$heslo', '$email', '$id_pravo', '$povolen'"; //string predany jako hodnota musi byt v ''
        //provedu dotaz a vratim vysledek
        return $this->insertIntoTable(TABLE_UZIVATELE, $insertStatements, $insetValues);
    }

    /**
     * Funkce smaze uzivatele s danym ID z databaze
     * @param int $id_user ID uzivatele
     * @return bool Vrati true, pokud smazani uzivatele probehne uspesne, jinak false
     */
    public function deleteUser($id_user)
    {
        return $this->deleteFromTable(TABLE_UZIVATELE, "id_uzivatel=$id_user");
    }

    /**
     * Funkce upravi udaje daneho uzivatele v databazi
     * @param int $id_uzivatel ID uzivatele
     * @param string $login Login uzivatele
     * @param string $heslo Heslo uzivatele
     * @param string $jmeno Jmeno uzivatele
     * @param string $email Email uzivatele
     * @param int $id_pravo ID prava
     * @return bool Vrati true pokud uprava udaju uzivatele probehne uspesne, jinak false
     */
    public function updateUser(int $id_uzivatel, string $login, string $heslo, string $jmeno, string $email, int $id_pravo, int $povolen)
    {
        //slozim cast s hodnotami
        $updateStatementWithValues = "login='$login', heslo='$heslo', jmeno='$jmeno', email='$email', id_pravo='$id_pravo', povolen='$povolen'";
        //podminka
        $whereStatement = "id_uzivatel='$id_uzivatel'";
        //provedu update
        return $this->updateInTable(TABLE_UZIVATELE, $updateStatementWithValues, $whereStatement);
    }

    public function updateUserRight(int $id_uzivatel, int $id_pravo)
    {
        //slozim cast s hodnotami
        $updateStatementWithValues = "id_pravo='$id_pravo'";
        //podminka
        $whereStatement = "id_uzivatel='$id_uzivatel'";
        return $this->updateInTable(TABLE_UZIVATELE, $updateStatementWithValues, $whereStatement);
    }

    public function updateBlockAllowUser(int $id_uzivatel, int $povolen)
    {
        $updateStatementWithValues = "povolen=$povolen";
        $whereStatement = "id_uzivatel='$id_uzivatel'";
        return $this->updateInTable(TABLE_UZIVATELE, $updateStatementWithValues, $whereStatement);
    }

    public function addNewArticle($nadpis, $abstrakt, $dokument, $datum, $id_uzivatel, $id_status = '1')
    {
        //hlavicka pro vlozeni do tabulky prispevky
        $insertStatements = "nadpis, abstrakt, dokument, datum, id_uzivatel, id_status"; //nadpis, abstrakt, ...
        //hodnoty pro vlozeni do tabulky prispevky
        $insertValues = "'$nadpis', '$abstrakt', '$dokument', '$datum','$id_uzivatel', $id_status";
        //provedu dotaz a vratim vysledek
        return $this->insertIntoTable(TABLE_PRISPEVKY, $insertStatements, $insertValues);
    }

    public function getArticlesByUser($id_uzivatel)
    {
        $articles = $this->selectFromTable(TABLE_PRISPEVKY, "*", "id_uzivatel=$id_uzivatel", "id_status");
        if (empty($articles)) {
            return null;
        } else {
            return $articles;
        }
    }

    public function deleteArticle($id_prispevek)
    {
        return $this->deleteFromTable(TABLE_PRISPEVKY, "id_prispevek='$id_prispevek'");
    }

    public function updateArticle($id_prispevek, $nadpis, $abstrakt) //doplnit dalsi atributy dle prispevku
    {
        $nadpis = htmlspecialchars($nadpis);
        $abstrakt = htmlspecialchars($abstrakt);

        $q = "UPDATE " . TABLE_PRISPEVKY . " SET nadpis=:nadpis, abstrakt=:abstrakt WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":nadpis", $nadpis);
        $res->bindValue(":abstrakt", $abstrakt);
        $res->bindValue(":id_prispevek", $id_prispevek);
        if ($res->execute()) return true;
        else return false;

    }

    public function getReviewsByUser($id_uzivatel)
    {
        $q = "SELECT h.id_hodnoceni, h.obsah, h.odbornost, h.jazyk, p.id_prispevek, s.id_status, s.nazev as status, p.nadpis, p.abstrakt, p.dokument, p.datum, CONCAT(u.jmeno, ' ', u.prijmeni) as autor
                FROM jandrlik_prispevky p,
                     jandrlik_hodnoceni h,
                     jandrlik_uzivatele u,
                     jandrlik_status s
                WHERE h.id_uzivatel = :id_recenzent
                AND   u.id_uzivatel = p.id_uzivatel
                AND   p.id_status = s.id_status
                AND   h.id_prispevek = p.id_prispevek
                ORDER BY s.id_status ASC, p.datum ASC";

        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_recenzent", $id_uzivatel);
        if ($res->execute()) return $res->fetchAll(PDO::FETCH_ASSOC);
        else return [];
    }

    public function addNewReview($id_recenzent, $id_prispevek)
    {
        //hlavicka pro vlozeni do tabulky prispevky
        $insertStatements = "";
        //hodnoty pro vlozeni do tabulky prispevky
        $insertValues = "' ', ' ', ' '";
        //provedu dotaz a vratim vysledek
        //return $this->insertIntoTable(TABLE_HODNOCENI, $insertStatements, $insertValues);
        $q = "INSERT INTO " . TABLE_HODNOCENI . "(id_uzivatel, id_prispevek) VALUES (:id_recenzent, :id_prispevek)";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_recenzent", $id_recenzent);
        $res->bindParam(":id_prispevek", $id_prispevek);
        if ($res->execute()) return true;
        else return false;
    }

    public function deleteReview($id_hodnoceni)
    {
        $q = "DELETE FROM " . TABLE_HODNOCENI . " WHERE id_hodnoceni = :id_hodnoceni";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_hodnoceni", $id_hodnoceni);
        if ($res->execute()) return true;
        else return false;
    }

    public function updateReview($id_hodnoceni)//doplnit dalsi atributy dle prispevku
    {
        //slozim cast s hodnotami
        $updateStatementWithValues = ""; // "klic='$hodnota', "
        //podminka
        $whereStatement = "id_hodnoceni='$id_hodnoceni'";
        //provedu dotaz a vratim vysledek
        return $this->updateInTable(TABLE_HODNOCENI, $updateStatementWithValues, $whereStatement);
    }


    public function getStatus($id_status)
    {
        $q = "SELECT nazev FROM " . TABLE_STATUS . " WHERE id_status=:id_status";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_status", $id_status);
        if ($res->execute()) return $res->fetchColumn();
        else return [];
    }


    public function changeArticleStatus($id_prispevek, $id_status)
    {
        $q = "UPDATE " . TABLE_PRISPEVKY . " SET id_status=:id_status WHERE id_prispevek=:id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindValue(":id_status", $id_status);
        $res->bindValue(":id_prispevek", $id_prispevek);
        if ($res->execute()) return true;
        else return false;
    }

    public function getCountReviews($id_prispevek)
    {
        $q = "SELECT COUNT(obsah) as countObsah, COUNT(jazyk) as countJazyk, COUNT(odbornost) as countOdbornost
                FROM jandrlik_hodnoceni
                WHERE id_prispevek = :id_prispevek";
        $res = $this->pdo->prepare($q);
        $res->bindParam(":id_prispevek", $id_prispevek);
        if ($res->execute()) return $res->fetch(PDO::FETCH_ASSOC);
        else return [];
    }

    //////////////////////////////// KONEC: Konkretni funkce ///////////////////////////////////////


}