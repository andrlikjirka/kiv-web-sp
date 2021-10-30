<?php

/**
 * Obalova trida pro praci s Cookies
 * @author jandrlik
 */
class Cookies
{
    /** @var int $defExpire Defaultni doba expirace cookie [s] */
    private $defExpire;

    /**
     * Konstruktor pri vytvoreni instance obalove tridy pro praci s Cookies nastavi defaultni dobu expirace na 10 dni
     */
    public function __construct()
    {
        //defaultni doba expirace = 10 dní v [s]
        $this->defExpire = 10 * 24 * 60 * 60;
    }

    /**
     * Funkce pro ulozeni hodnoty do Cookis s danym klicem
     * @param string $name Jmeno klice
     * @param mixed $value Hodnota
     * @param null $expire Doba expirace Cookie, defaultne null
     */
    public function addCookie(string $name, $value, $expire = null)
    {
        if (!isset($expire)) {
            $expire = $this->defExpire;
        }
        //nastaveni cookie
        setcookie($name, $value, time() + $expire);
    }

    /**
     * Funkce kontroluje, zda je Cookie s danym klicem nastavena
     * @param string $name Jmeno klice
     * @return bool Vraci true pokud je cookie s danym klicem nastavena, jinak false
     */
    public function isCookieSet(string $name): bool
    {
        return isset($_COOKIE[$name]);
    }

    /**
     * Funkce zajistuje cteni hodnoty v Cookies s danym klicem
     * @param string $name Jmeno klice
     * @return mixed|null Vraci hodnotu ukozenou v Cookies s danym klicem, jinak null
     */
    public function readCookie(string $name)
    {
        if ($this->isCookieSet($name)) {
            return $_COOKIE[$name];
        } else {
            return null;
        }

    }

    /**
     * Funkce odstrani Cookie s danym klicem
     * @param string $name Jmeno klice
     */
    public function removeCookie(string $name)
    {
        $this->addCookie($name, null, 0); //cookie se smaze nastavenim hodnoty null a doby expirace na 0
    }

}