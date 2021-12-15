/// funkce, které dělají manipulaci s daty do modalu, vratit je do sablon!!!!!


//// My Articles ////


/**
 * Funkce pri stistku tlacitka Smazat clanek spusti confirm box
 * @param event Udalost
 */
function deleteArticle(event) {
    if (!confirm("Opravdu chcete smazat příspěvek?")) {
        event.preventDefault();
    }
}

//// Registration ////
/**
 * Funkce overuje shodnost zadavanych hesel pri registraci
 */
function overeniHesel() {
    var heslo = document.getElementById("lb_password").value;
    var heslo2 = document.getElementById("lb_confirm_password").value;
    var outputElem = document.getElementById("overeni-hesla");

    if (heslo === heslo2) {
        outputElem.className = "text-success fw-bold"
        outputElem.value = 'Hesla jsou stejná';
    } else {
        outputElem.className = "text-danger fw-bold"
        outputElem.value = 'Hesla nejsou stejná';
    }
}

//// MyReviews ////
/**
 * Funkce po stisku tlacitka 'Recenzovat' priradi id_hodnoceni do hidden inputu prislusneho modalu
 * @param id_hodnoceni ID recenze
 */
function recenzovat(id_hodnoceni) {
    document.getElementById('id_hodnoceni').value = id_hodnoceni;
}

/**
 * Funkce po stisku tlacitka 'Upravit recenzi' priradi potrebna data do prislusneho modalu
 * @param id_hodnoceni ID recenze
 * @param obsah Hodnoceni obsahu
 * @param jazyk Hodnoceni jazyka
 * @param odbornost Hodnoceni odbornosti
 */
function upravitRecenzi(id_hodnoceni, obsah, jazyk, odbornost) {
    document.getElementById('id_hodnoceni').value = id_hodnoceni;
    document.getElementById('input-obsah').value = obsah;
    document.getElementById('input-jazyk').value = jazyk;
    document.getElementById('input-odbornost').value = odbornost;
}

//// ArticlesManagement ////
/**
 * Funkce pri stistku tlacitka Smazat recenzenta spusti confirm box
 * @param event Udalost
 */
function deleteReviewer(event) {
    if (!confirm("Opravdu chcete odstranit recenzenta?")) {
        event.preventDefault();
    }
}

//// User Management ////
/**
 * Funkce pri stistku tlacitka Smazat uzivatele spusti confirm box
 * @param event Udalost
 */
function deleteUser(event) {
    if (!confirm("Opravdu chcete smazat uživatele?")) {
        event.preventDefault();
    }
}

/**
 * Funkce pri stistku tlacitka Zablokovat uzivatele spusti confirm box
 * @param event
 */
function blockUser(event) {
    if (!confirm("Opravdu chcete zablokovat uživatele?")) {
        event.preventDefault();
    }
}

/**
 * Funkce pri stistku tlacitka Povolit uzivatele spusti confirm box
 * @param event
 */
function allowUser(event) {
    if (!confirm("Opravdu chcete povolit uživatele?")) {
        event.preventDefault();
    }
}

//// Zakaz znovuodeslani formulare pri reloadu stranky ////
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
