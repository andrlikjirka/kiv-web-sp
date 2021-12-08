<?php

namespace kivweb_sp\views;

class TemplateBasics implements IView
{
    /** @var string PAGE_INTRODUCTION Sablona s uvodni strankou */
    const PAGE_INTRODUCTION = "IntroductionTemplate.tpl.php";
    /** @var string PAGE_ACCEPTED_ARTICLES Sablona se strankou publikovanych clanku */
    const PAGE_PUBLISHED_ARTICLES = "PublishedArticlesTemplate.tpl.php";
    /** @var string PAGE_LOGIN Sablona s login strankou */
    const PAGE_LOGIN = "LoginTemplate.tpl.php";
    /** @var string PAGE_REGISTRATION Sablona s registracni strankou */
    const PAGE_REGISTRATION = "RegistrationTemplate.tpl.php";
    /** @var string PAGE_USER_MANAGEMENT Sablona se spravou uzivatelu */
    const PAGE_USER_MANAGEMENT = "UserManagementTemplate.tpl.php";
    /** @var string PAGE_USER_MANAGEMENT Sablona se spravou clanku */
    const PAGE_ARTICLES_MANAGEMENT = "ArticlesManagementTemplate.tpl.php";
    /** @var string PAGE_MY_ARTICLES Sablona pro moje autorske clanky */
    const PAGE_MY_ARTICLES = "MyArticlesTemplate.tpl.php";
    /** @var string PAGE_MY_REVIEWS Sablona pro moje recenze */
    const PAGE_MY_REVIEWS = "MyReviewsTemplate.tpl.php";

    /**
     * Zajisti vypsani HTML sablony prislusne stranky
     * @param array $templateData Data stranky
     * @param string $pageType Typ vypisovane stranky
     */
    public function printTemplate(array $templateData, string $pageType = self::PAGE_INTRODUCTION)
    {
        // vypis hlavicky
        $this->getHTMLHead($templateData['title']);

        $this->getHTMLNav($templateData);

        //vypis sablony obsahu
        //data pro sablonu nastavim na globalni
        global $tplData;
        $tplData = $templateData;
        // nactu sablonu
        require_once($pageType);

        // vypis paticky
        $this->getHTMLFooter();
    }

    /**
     * Funkce vrati uvod html stranky (head)
     * @param string $pageTitle Nazev stranky
     */
    private function getHTMLHead(string $pageTitle)
    {
        ?>
        <!DOCTYPE html>
        <html lang="cs">
        <head>
            <meta charset="UTF-8">
            <meta name="author" content="jandrlik">
            <meta name="description" content="Semestrální práce WEB">
            <meta name="keywords" content="kiv web sp">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title><?php echo $pageTitle ?></title>
            <link rel="icon" type="image/png" href="public/img/favicon.ico">

            <!-- CSS -->
            <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.css">
            <link rel="stylesheet" href="node_modules/bootstrap-icons/font/bootstrap-icons.css">
            <link rel="stylesheet" type="text/css" href="public/css/styly.css">
            <script src="node_modules/ckeditor/ckeditor.js"></script>

        </head>
        <?php
    }

    /**
     * Funkce vrati navigaci stranky
     */
private function getHTMLNav(array $tplData)
{
    ?>
<body data-bs-spy="scroll" data-bs-target="#navigace">

    <!-- Navbar-->
    <nav id="navigace" class="navbar navbar-expand-lg navbar-light bg-white fixed-top py-3 shadow-sm">
        <div class="container px-5">
                <span class="navbar-brand text-success fw-bold">
                    ECO <sup>2022</sup>
                </span>
            <button class="navbar-toggler collapsed" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false"
                    aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarTogglerDemo02">
                <ul class="navbar-nav m-auto ">
                    <?php
                    foreach (WEB_PAGES as $key => $pageInfo) {
                        if ($key == "uvod" or $key == "publikovane_clanky") {
                            echo "<li class='nav-item me-3'>
                                        <a class='nav-link' href='index.php?page=$key'>$pageInfo[title]</a>
                                      </li>";
                        }
                    }
                    ?>
                </ul>
                <?php
                if ($tplData['isUserLoggedIn'] == false) {
                    foreach (WEB_PAGES as $key => $pageInfo) {
                        if ($key == "login") {
                            echo "<a class='btn btn-success px-3 py-1 me-2' href='index.php?page=$key'>$pageInfo[title]</a>";
                        } else if ($key == "registrace") {
                            echo "<a class='btn btn-outline-success px-3 py-1' href='index.php?page=$key'>$pageInfo[title]</a>";
                        }
                    }
                } else {
                    ?>

                    <div class="dropdown">
                        <button class="btn btn-success dropdown-toggle px-3 py-1" type="button" id="dropdownMenuButton1"
                                data-bs-toggle="dropdown" aria-expanded="false">
                            <?php echo $tplData['userData']['jmeno'] . " " . $tplData['userData']['prijmeni'] ?>
                        </button>
                        <ul class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                            <?php
                            if ($tplData['userData']['id_pravo'] <= 2) { //admin nebo Superadmin
                                echo "<li><a class='dropdown-item' href='index.php?page=sprava_clanku'>Správa článků</a></li>";
                                echo "<li><a class='dropdown-item' href='index.php?page=sprava_uzivatelu'>Správa uživatelů</a></li>";
                            } else if ($tplData['userData']['id_pravo'] == 3) { //recenzent
                                echo "<li><a class='dropdown-item' href='index.php?page=moje_recenze'>Moje recenze</a></li>";
                            } else { //autor
                                echo "<li><a class='dropdown-item' href='index.php?page=moje_clanky'>Moje články</a></li>";
                            }
                            ?>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li class="dropdown-item">
                                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST"
                                      class="mb-0">
                                    <input type="hidden" name="action" value="logout">
                                    <!--<input type="submit" name="potvrzeni" value="Odhlásit">-->
                                    <button type="submit" name="potvrzeni" class="btn btn-outline-success small py-1">
                                        Odhlásit
                                        <span class="bi bi-box-arrow-right ms-2"></span>
                                    </button>

                                </form>
                            </li>
                        </ul>
                    </div>
                    <?php
                }
                ?>

            </div>
        </div>
    </nav>
    <?php
}

    /**
     * Funkce vrati paticku stranky
     */
    private function getHTMLFooter()
    {
        ?>

        <!-- Paticka -->
        <footer class="pt-4 pb-2 bg-white bg-gradient">
            <div class="container px-5 text-center">
                <div class="pb-3">
                    <a href="https://www.facebook.com/" target="_blank" class="text-decoration-none text-success me-3">
                        <i
                                class="bi bi-facebook"></i> </a>
                    <a href="https://www.instagram.com/" target="_blank" class="text-decoration-none text-success me-3">
                        <i
                                class="bi bi-instagram"></i> </a>
                    <a href="https://www.twitter.com/" target="_blank" class="text-decoration-none text-success "> <i
                                class="bi bi-twitter"></i> </a>
                </div>
                <div class="small text-secondary">
                    <p>&copy; 2021, Jiří Andrlík</p>
                </div>
            </div>
        </footer>


        <!-- Javascripty -->
        <script src="node_modules/jquery/dist/jquery.js"></script>
        <script src="node_modules/bootstrap/dist/js/bootstrap.min.js"></script>

        <!-- Konec: Javascripty-->
        </body>
        </html>

        <?php
    }

}