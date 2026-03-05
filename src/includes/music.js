let playing = false;

// If the URL contains /make/ AND NOT index.php or graphics.php, send playing
// If the URL contains /games/play.php, send playing
// Else, send paused

if ((window.location.pathname.includes("/make/") && !window.location.pathname.includes("index.php") && !window.location.pathname.includes("graphics.php")) || window.location.pathname.includes("/games/play.php")) {
    window.parent.postMessage({ type: "iframe-audio", state: "playing" }, "*");
} else {
    window.parent.postMessage({ type: "iframe-audio", state: "paused" }, "*");
}