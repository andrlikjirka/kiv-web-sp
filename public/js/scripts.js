//// My Articles ////
function prirad(id, nazev, abstrakt) {
    document.getElementById("article_id").value = id;
    document.getElementById("edit-nazev-clanku").value = nazev;
    document.getElementById("edit-abstrakt").value = abstrakt;
}

function deleteArticle(event) {
    if (!confirm("Opravdu chcete smazat příspěvek?")) {
        event.preventDefault();
    }
}

//// Registration ////
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
function recenzovat(id_hodnoceni) {
    document.getElementById('id_hodnoceni').value = id_hodnoceni;
}

function upravitRecenzi(id_hodnoceni, obsah, jazyk, odbornost) {
    document.getElementById('id_hodnoceni').value = id_hodnoceni;
    document.getElementById('input-obsah').value = obsah;
    document.getElementById('input-jazyk').value = jazyk;
    document.getElementById('input-odbornost').value = odbornost;
}

//// ArticlesManagement ////
function deleteReviewer(event) {
    if (!confirm("Opravdu chcete odstranit recenzenta?")) {
        event.preventDefault();
    }
}

//// User Management ////
function deleteUser(event) {
    if (!confirm("Opravdu chcete smazat uživatele?")) {
        event.preventDefault();
    }
}

function blockUser(event) {
    if (!confirm("Opravdu chcete zablokovat uživatele?")) {
        event.preventDefault();
    }
}

function allowUser(event) {
    if (!confirm("Opravdu chcete povolit uživatele?")) {
        event.preventDefault();
    }
}

//// Zakaz znovuodeslani formulare pri reloadu stranky ////
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
