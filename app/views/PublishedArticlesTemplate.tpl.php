<?php
namespace kivweb_sp\views;

/////////////////////////////////////////////////////////////
////// Sablona pro zobrazeni stranky moje recenze  ///////
/////////////////////////////////////////////////////////////

// urceni globalnich promennych, se kterymi sablona pracuje
global $tplData;

?>
<!-- Publikovane clanky -->
<section class="bg-light py-5">
    <div class="container py-5">
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-10">
                <h3 class="text-dark"><?php echo $tplData['title'] ?> </h3>
            </div>
        </div>
        <div class="row mb-5 justify-content-center">
            <div class="col-lg-10">

                <?php
                $articles = "";
                $prispevky = $tplData['publikovanePrispevky'];

                foreach ($prispevky as $prispevek) {

                    $articles .= "
                     <article>
                    <a class='link-dark text-decoration-none ' href='index.php?page=zobrazeni&dokument=". $prispevek['dokument'] ."' target='_blank' rel='noopener'>
                        <h5>$prispevek[nadpis]</h5>
                        <p class='text-dark'>" . htmlspecialchars_decode($prispevek['abstrakt']) . "</p>
                    </a> 
                    <div> 
                        <div class='text-secondary small'>$prispevek[autor]</div>
                        <div class='text-secondary small'>$prispevek[datum]</div>
                    </div>
                    <hr class='my-4'>
                </article>
                    ";

                }
                echo $articles;

                ?>

            </div>
        </div>

    </div>
</section>

