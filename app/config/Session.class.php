<?php

namespace kivweb_sp\config;
/**
 * Obalova trida pro praci se Session
 * (vyuziti navrhoveho vzoru Singleton)
 * @author jandrlik
 */

class Session
{
    /** @var Session $session Jedina instance obalove tridy MySession */
    private static $session;

    /**
     * Bezparametricky privatni konstruktor - volan pouze jednou, pokud neni nastavena zadna $_SESSION
     * Pri vytvoreni objektu je zahajena session
     */
    private function __construct()
    {
        session_start();
    }

    /**
     * Staticka tovarni metoda
     * @return Session Vraci jedinou instanci obalove tridy Session
     */
    public static function getSession()
    {
        if (!isset($_SESSION)) {
            self::$session = new Session();
        }
        return self::$session;
    }

    /**
     * Funkce pro ulozeni hodnoty do session
     * @param string $name Jmeno klice
     * @param mixed $value Hodnota klice
     */
    public function setSession(string $name, $value)
    {
        $_SESSION[$name] = $value;
    }

    /**
     * Funkce pro kontrolu, jestli je Session s danym klicem nastavena
     * @param string $name Jmeno klice
     * @return bool Vraci true pokud je v Session dany klic, nebo false pokud neni
     */
    public function isSessionSet(string $name): bool
    {
        return isset($_SESSION[$name]);
    }

    /**
     * Funkce cte hodnotu Session s danym klicem
     * @param string $name Jmeno klice
     * @return mixed|null Vraci hodnotu Session s danym klicem nebo null
     */
    public function readSession(string $name)
    {
        if ($this->isSessionSet($name)) {
            return $_SESSION[$name];
        }
        return null;
    }

    /**
     * Funkce odstrani session s danym klicem
     * @param string $name Jmeno klice
     */
    public function removeSession(string $name)
    {
        unset($_SESSION[$name]);
    }

    /**
     * Funkce odstrani celou session
     */
    public function removeAllSession()
    {
        session_unset();
    }

}
