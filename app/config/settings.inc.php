<?php
//////////////////////////////////////////////////////
////////////// Zakladni nastaveni ////////////////////
//////////////////////////////////////////////////////

////// nastaveni pristupu k databazi ///////

// prihlasovaci udaje k databazi
define("DB_SERVER", "localhost");
define("DB_NAME", "kiv-web-sp");
define("DB_USER", "root");
define("DB_PASSWORD", "");


// definice konkretnich nazvu tabulek
define("TABLE_UZIVATELE", "jandrlik_uzivatele");
define("TABLE_PRAVA", "jandrlik_prava");
define("TABLE_PRISPEVKY", "jandrlik_prispevky");
define("TABLE_HODNOCENI", "jandrlik_hodnoceni");


////// nastaveni konstant pro autoloader ///////

/** @var string BASE_NAMESPACE_NAME Zakladni namespace */
const BASE_NAMESPACE_NAME = "kivweb_sp";
/** @var string BASE_APP_DIR_NAME Vychozi adresar aplikace */
const BASE_APP_DIR_NAME ="app";
/** @var array FILE_EXTENSIONS Dostupne pripony souboru, ktere budou testovany pri nacitani souboru pozadovanych trid */
const FILE_EXTENSIONS = array(".class.php", ".interface.php");

