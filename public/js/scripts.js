//// Zakaz znovuodeslani formulare pri reloadu stranky ////
if (window.history.replaceState) {
    window.history.replaceState(null, null, window.location.href);
}
