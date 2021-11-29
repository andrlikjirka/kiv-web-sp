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
//tabulka status

////// nastaveni konstant pro autoloader ///////

/** @var string BASE_NAMESPACE_NAME Zakladni namespace */
const BASE_NAMESPACE_NAME = "kivweb_sp";
/** @var string BASE_APP_DIR_NAME Vychozi adresar aplikace */
const BASE_APP_DIR_NAME ="app";
/** @var array FILE_EXTENSIONS Dostupne pripony souboru, ktere budou testovany pri nacitani souboru pozadovanych trid */
const FILE_EXTENSIONS = array(".class.php", ".interface.php");

//// Dostupne stranky webu ////
const DEFAULT_WEB_PAGE_KEY = "uvod";

const WEB_PAGES = array(
    //// Uvodni stranka ////
    "uvod" => array(
        "title" => "O konferenci",
        "controller_class_name" => \kivweb_sp\controllers\IntroductionController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_INTRODUCTION,
    ),
    //// KONEC: Uvodni stranka ////

    //// Publikovane clanky ////
    "publikovane_clanky" => array(
        "title" => "Publikované články",
        "controller_class_name" => \kivweb_sp\controllers\PublishedArticlesController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_PUBLISHED_ARTICLES,
    ),
    //// KONEC: Publikovane clanky ////

    //// Login stranka ////
    "login" => array(
        "title" => "Přihlásit",
        "controller_class_name" => \kivweb_sp\controllers\LoginController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_LOGIN,
    ),
    //// KONEC: Login stranka ////

    //// Registrace stranka ////
    "registrace" => array(
        "title" => "Registrace",
        "controller_class_name" => \kivweb_sp\controllers\RegistrationController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_REGISTRATION,
    ),
    //// KONEC: Registrace stranka ////

    //// Sprava uzivatelu ////
    "sprava_uzivatelu" => array(
        "title" => "Správa uživatelů",
        "controller_class_name" => \kivweb_sp\controllers\UserManagementController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_USER_MANAGEMENT,
    ),
    /// KONEC: Sprava uzivatelu ////

    //// Sprava clanku ////
    "sprava_clanku" => array(
        "title" => "Správa článků",
        "controller_class_name" => \kivweb_sp\controllers\ArticlesManagementController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_ARTICLES_MANAGEMENT,
    ),
    /// KONEC: Sprava clanku ////

    //// Moje clanky ////
    "moje_clanky" => array(
        "title" => "Moje články",
        "controller_class_name" => \kivweb_sp\controllers\MyArticlesController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_MY_ARTICLES,
    ),
    //// KONEC: Moje clanky ////

    //// Moje recenze ////
    "moje_recenze" => array(
        "title" => "Moje recenze",
        "controller_class_name" => \kivweb_sp\controllers\MyReviewsController::class,
        "view_class_name" => \kivweb_sp\views\TemplateBasics::class,
        "template_type" => \kivweb_sp\views\TemplateBasics::PAGE_MY_REVIEWS,
    )
    /// KONEC: Moje recenze ////


);


