<?php

namespace kivweb_sp\models;

use PDO;
use PDOException;

/**
 * Obalova trida pro praci s databazi pomoci PDO
 * (vyuziti navrhoveho vzoru Singleton)
 */
class Database
{
    /** @var Database $dbConnection Jedina instance obalove tridy pro praci s databazi */
    private static $dbConnection;

    /** @var \PDO objekt pro praci s databazi */
    private $pdo;

    /**
     * Konstruktor inicializuje pripojeni k databazi
     */
    private function __construct()
    {
        try {
            $this->pdo = new PDO("mysql:host=".DB_SERVER."; dbname=".DB_NAME."", DB_USER, DB_PASSWORD);
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
    public static function getDBConnection()
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
    public function selectFromTable($tableName, $columnsNames = "*", $whereStatement="", $orderByStatement=""):array
    {
        //slozim dotaz
        $query = "SELECT $columnsNames FROM $tableName"
            .((empty($whereStatement)) ? "" : " WHERE $whereStatement")
            .((empty($orderByStatement)) ? "" : " ORDER BY $orderByStatement");
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
    private function insertIntoTable(string $tableName, string $insertStatement, string $insertValues):bool
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
        return $this->selectFromTable(TABLE_UZIVATELE, "*", "", "jmeno");
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
        return $this->selectFromTable(TABLE_PRISPEVKY);
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
        $user = $this->selectFromTable(TABLE_UZIVATELE, "*","id_uzivatel='$id_uzivatel'", "");
        //mam pole s jednou hodnotou
        if (empty($user)) {
            return null;
        } else {
            return $user[0]; //vratim prvni hodnotu (udaje o jednom uzivateli)
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
        $right = $this->selectFromTable(TABLE_PRAVA,"*", "id_pravo='$id'");
        if (empty($right)) {
            return null;
        } else {
            //vracim nalezene pravo (prvni v poli prav ktere mi vraci select)
            return $right[0];
        }
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
    public function addNewUser(string $jmeno, string $login, string $heslo, string $email, int $id_pravo = 4){
        //hlavicka pro vlozeni do tabulky uzivatelu
        $insertStatements = "jmeno, login, heslo, email, id_pravo";
        //hodnoty pro vlozeni do tabulky uzivatelu
        $insetValues = "'$jmeno', '$login', '$heslo', '$email', $id_pravo"; //string predany jako hodnota musi byt v ''
        //provedu dotaz a vratim vysledek
        return $this->insertIntoTable(TABLE_UZIVATELE, $insertStatements, $insetValues);
    }

    /**
     * Funkce smaze uzivatele s danym ID z databaze
     * @param int $id_user ID uzivatele
     * @return bool Vrati true, pokud smazani uzivatele probehne uspesne, jinak false
     */
    public function deleteUser($id_user){
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
    public function updateUser(int $id_uzivatel, string $login, string $heslo, string $jmeno, string $email, int $id_pravo)
    {
        //slozim cast s hodnotami
        $updateStatementWithValues = "login='$login', heslo='$heslo', jmeno='$jmeno', email='$email', id_pravo='$id_pravo'";
        //podminka
        $whereStatement = "id_uzivatel='$id_uzivatel'";
        //provedu update
        return $this->updateInTable(TABLE_UZIVATELE, $updateStatementWithValues, $whereStatement);
    }

    public function addNewArticle()
    {
        //hlavicka pro vlozeni do tabulky prispevky
        $insertStatements = ""; //nadpis, abstrakt, ...
        //hodnoty pro vlozeni do tabulky prispevky
        $insertValues = "' ', ' ', ' '";
        //provedu dotaz a vratim vysledek
        return $this->insertIntoTable(TABLE_PRISPEVKY, $insertStatements, $insertValues);
    }

    public function deleteArticle($id_prispevek)
    {
        return $this->deleteFromTable(TABLE_PRISPEVKY, "id_prispevek='$id_prispevek'");
    }

    public function updateArticle($id_prispevek ) //doplnit dalsi atributy dle prispevku
    {
        //slozim cast s hodnotami
        $updateStatementWithValues = ""; // "klic='$hodnota', "
        //podminka
        $whereStatement = "id_prispevek='$id_prispevek'";
        //provedu dotaz a vratim vysledek
        return $this->updateInTable(TABLE_PRISPEVKY, $updateStatementWithValues, $whereStatement);
    }

    public function addNewReview()
    {
        //hlavicka pro vlozeni do tabulky prispevky
        $insertStatements = "";
        //hodnoty pro vlozeni do tabulky prispevky
        $insertValues = "' ', ' ', ' '";
        //provedu dotaz a vratim vysledek
        return $this->insertIntoTable(TABLE_HODNOCENI, $insertStatements, $insertValues);
    }

    public function deleteReview($id_hodnoceni)
    {
        return $this->deleteFromTable(TABLE_HODNOCENI, "id_hodnoceni='$id_hodnoceni'");
    }

    public function updateReview($id_hodnoceni )//doplnit dalsi atributy dle prispevku
    {
        //slozim cast s hodnotami
        $updateStatementWithValues = ""; // "klic='$hodnota', "
        //podminka
        $whereStatement = "id_hodnoceni='$id_hodnoceni'";
        //provedu dotaz a vratim vysledek
        return $this->updateInTable(TABLE_HODNOCENI, $updateStatementWithValues, $whereStatement);
    }

    //////////////////////////////// KONEC: Konkretni funkce ///////////////////////////////////////


}