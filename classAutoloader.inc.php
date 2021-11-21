<?php
/**
 * Funkce pro automatickou registraci pozadovanych trid
 */
spl_autoload_register(function ($className) {
    //v nazvu tridy upravim vychozi adresar
    $className = str_replace(BASE_NAMESPACE_NAME, BASE_APP_DIR_NAME, $className);
    //slozim celou cestu k souboru bez pripony
    $fileName = dirname(__FILE__) . "\\" . $className;

    foreach (FILE_EXTENSIONS as $extension) {
        if (file_exists($fileName . $extension)) {
            $fileName .= $extension;
            break;
        }
    }

    //pripojim soubor s pozadovanou tridou
    require_once($fileName);
});

?>
