function featureGame(gameId, feature = true) {
    const linkElement = document.getElementById('featureGameLink');
    if (!linkElement) return;
    // Ask for confirmation
    const actionText = feature ? 'feature' : 'unfeature';
    if (!confirm(`Are you sure you want to ${actionText} this game?`)) {
        return;
    }
    // Store previous style and text
    const prevStyle = linkElement.getAttribute('style') || '';
    const prevText = linkElement.textContent;
    linkElement.textContent = 'Working...';
    linkElement.style.cssText = prevStyle + ';color:white;pointer-events:none;cursor:default;';

    // Build the request URL
    const url = `/php/feature.php?g_id=${encodeURIComponent(gameId)}&feature=${feature}`;

    fetch(url, { credentials: 'same-origin' })
        .then(response => response.text())
        .then(result => {
            if (result.trim() === "Success") {
                // Toggle the link text and action
                if (feature) {
                    linkElement.textContent = 'Unfeature Game';
                    linkElement.onclick = function() { featureGame(gameId, false); return false; };
                } else {
                    linkElement.textContent = 'Feature Game';
                    linkElement.onclick = function() { featureGame(gameId, true); return false; };
                }
                linkElement.style.cssText = prevStyle;
            } else {
                linkElement.textContent = result;
                setTimeout(() => {
                    linkElement.textContent = prevText;
                    linkElement.style.cssText = prevStyle;
                }, 2500);
            }
        })
        .catch(() => {
            linkElement.textContent = 'Error';
            setTimeout(() => {
                linkElement.textContent = prevText;
                linkElement.style.cssText = prevStyle;
            }, 2000);
        });
}