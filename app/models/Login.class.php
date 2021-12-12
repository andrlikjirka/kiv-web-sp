<?php

namespace kivweb_sp\models;

/**
 * Trida pro spravu prihlaseni uzivatele
 * @author jandrlik
 */
class Login
{
    /** @var Database $db Objekt pro praci s databazi */
    private $db;

    /** @var Session $session Objekt pro praci se session */
    private $session;

    /** @var string Klic, pod kterym budu pristupovat do session */
    private const SESSION_KEY = "logged_user_id"; //cim budu pristupovat do Session

    /**
     * Konstruktor inicializuje objekt Session a ziska pripojeni k databazi
     */
    public function __construct()
    {
//        require_once("../Session_Cookies/Session.class.php");
        $this->session = Session::getSession();
        //require_once("../database/Database.class.php");
        $this->db = Database::getDBConnection();
    }

    /**
     * Otestuje, zda je uzivatel prihlasen (zda je zaznam v session)
     * @return bool Vrati true pokud je uzivatel prihlasen, jinak false
     */
    public function isUserLoggedIn()
    {
        return $this->session->isSessionSet(self::SESSION_KEY);
    }

    /**
     * Funkce zajistuje prihlaseni uzivatele
     * @param string $login Login uzivatele
     * @param string $password Heslo uzivatele
     * @return bool Vrati true pokud prihlaseni uzivatele probehne uspesne, jinak false
     */
    public function login(string $login, string $password)
    {
        $user = $this->db->getUserWithLogin($login);
        //ziskal jsem uzivatele
        if (count($user) && $user[0]['povolen'] == 1) {
            if (password_verify($password, $user[0]['heslo'])) {
                //ziskal - ulozim jeho ID do Session
                $this->session->setSession(self::SESSION_KEY, $user[0]['id_uzivatel']); //beru prvniho nalezeneho a ukladam jen jeho ID
                return true;
            } else {
                //echo "ERROR: Zadane heslo není správné.";
                return false;
            }
        } else {
            //neziskal jsem uzivatele
            return false;
        }
    }

    /**
     * Funkce odhlasi uzivatele
     */
    public function logout()
    {
        $this->session->removeSession(self::SESSION_KEY);
    }

    /**
     * Funkce ziska data prihlaseneho uzivatele
     * @return mixed|null Vrati data uzivatele, nebo null
     */
    public function getLoggedUserData()
    {
        if ($this->isUserLoggedIn()) {
            //ziskam id uzivatele ze session
            $userId = $this->session->readSession(self::SESSION_KEY);
            //pokud nemam id uzivatele, tak vypisu chybu a vynutim odhlaseni
            if ($userId == null) {
                //nemam id uzivatele ze session - vypisu chybu, uzivatele odhlasim, vratim null
                echo "SERVER ERROR: Data prihlaseneho uzivatele nenalezena, a proto byl uživatel odhlášen";
                $this->logout();
                return null;
            } else {
                //nactu data uzivatele z db
                $userData = $this->db->getUserByID($userId); //ziskam udaje o jednom uzivateli s danym id (ale v DB muze byt jen jeden uzivatel s danym loginem, takze OK)
                //mam data uzivatele?
                if (empty($userData)) {
                    //nemam
                    echo "ERROR: Data přihlášeného uživatele se nenachází v DB, a proto byl uživatel odhlášen.";
                    $this->logout();
                    return null;
                } else {
                    //protoze DB vraci pole uzivatelu, tak vratim jeho prvni polozku (prvniho uzivatele)
                    return $userData;
                }
            }
        } else {
            //uzivatel neni prihlasen
            return null;
        }
    }



}