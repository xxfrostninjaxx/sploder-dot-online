document.addEventListener("DOMContentLoaded", function () {
    const speedRunRadio = document.querySelector('input[name="choice"][value="0"]');
    const getScoreRadio = document.querySelector('input[name="choice"][value="1"]');
    const scoreLabel = document.querySelector('label[for="name"]');
    const scoreSuffix = document.querySelector('td.suffix');
    const challengePrizeInput = document.querySelector('input[name="prize"]');
    const maxWinnersInput = document.querySelector('input[name="winners"]');
    const challengeInput = document.querySelector('input[name="challenge"]');
    const costDisplay = document.querySelector('td[colspan="3"] mark');
    const createButton = document.querySelector('input[value="Create"]');


    
    speedRunRadio.addEventListener("change", function () {
        if (speedRunRadio.checked) {
            scoreLabel.textContent = "Win in less than";
            scoreSuffix.textContent = "seconds";
        }
    });

    if (getScoreRadio) {
        getScoreRadio.addEventListener("change", function () {
            if (getScoreRadio.checked) {
                scoreLabel.textContent = "Score at least";
                scoreSuffix.textContent = "pts";
            }
        });
    }

    function updateCost() {
        const prize = parseInt(challengePrizeInput.value) || 0;
        const winners = parseInt(maxWinnersInput.value) || 0;
        const cost = prize * winners;
        const challenge = parseInt(challengeInput.value) || 0;
        costDisplay.textContent = `${cost}`;

        if (boostPoints < cost || cost < 150 || prize < 50 || winners < 1 || challenge < 1) {
            createButton.disabled = true;
        } else {
            createButton.disabled = false;
        }
    }

    challengePrizeInput.addEventListener("input", updateCost);
    maxWinnersInput.addEventListener("input", updateCost);
});