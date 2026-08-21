const MAX_LIVES = 5;
const LIFE_RECOVERY_MS = 30 * 60 * 1000;
const LIVES_STORAGE_KEY = "candyLives";
const LIFE_TIMER_STORAGE_KEY = "candyLifeRecoveryAt";

const savedLives = localStorage.getItem(LIVES_STORAGE_KEY);
const parsedLives = Number(savedLives);
state.lives = savedLives === null
    ? MAX_LIVES
    : Math.max(0, Math.min(MAX_LIVES, Number.isFinite(parsedLives) ? parsedLives : MAX_LIVES));

let recoveryTimer = null;

function saveLives() {
    localStorage.setItem(LIVES_STORAGE_KEY, String(state.lives));
}

function getRecoveryAt() {
    const value = Number(localStorage.getItem(LIFE_TIMER_STORAGE_KEY));
    return Number.isFinite(value) && value > 0 ? value : 0;
}

function setRecoveryAt(timestamp) {
    if (timestamp > 0) {
        localStorage.setItem(LIFE_TIMER_STORAGE_KEY, String(timestamp));
    } else {
        localStorage.removeItem(LIFE_TIMER_STORAGE_KEY);
    }
}

function getLivesText() {
    return "❤️".repeat(state.lives) + "🖤".repeat(MAX_LIVES - state.lives);
}

function formatRecoveryTime(ms) {
    const totalSeconds = Math.max(0, Math.ceil(ms / 1000));
    const minutes = Math.floor(totalSeconds / 60);
    const seconds = totalSeconds % 60;
    return `${String(minutes).padStart(2, "0")}:${String(seconds).padStart(2, "0")}`;
}

function renderLives() {
    const display = document.getElementById("livesDisplay");
    if (!display) return;

    const recoveryAt = getRecoveryAt();
    const remaining = recoveryAt - Date.now();

    let recoveryHtml = "";

    if (state.lives < MAX_LIVES && remaining > 0) {
        recoveryHtml = `
            <span class="livesRecovery">
                ❤️ Próxima vida en <strong id="lifeTimer">${formatRecoveryTime(remaining)}</strong>
            </span>
        `;
    } else if (state.lives < MAX_LIVES) {
        recoveryHtml = `
            <span class="livesRecovery">
                ❤️ Recuperando vida...
            </span>
        `;
    } else {
        recoveryHtml = `
            <span class="livesRecovery livesFull">
                ✅ Vidas completas
            </span>
        `;
    }

    display.innerHTML = `
        <div class="livesTopRow">
            <span class="livesLabel">❤️ Vidas</span>
            <span class="livesHearts" aria-label="${state.lives} de ${MAX_LIVES} vidas">
                ${getLivesText()}
            </span>
        </div>
        ${recoveryHtml}
    `;

    display.classList.toggle("noLives", state.lives <= 0);

    const devButton = document.getElementById("restoreLivesButton");
    if (devButton) {
        devButton.hidden = state.lives === MAX_LIVES;
    }
}

function hasLives() {
    return state.lives > 0;
}

function recoverAvailableLives() {
    if (state.lives >= MAX_LIVES) {
        setRecoveryAt(0);
        return;
    }

    let recoveryAt = getRecoveryAt();
    if (!recoveryAt) {
        recoveryAt = Date.now() + LIFE_RECOVERY_MS;
        setRecoveryAt(recoveryAt);
    }

    const now = Date.now();
    const elapsed = now - recoveryAt;

    if (elapsed < 0) return;

    const recovered = 1 + Math.floor(elapsed / LIFE_RECOVERY_MS);
    state.lives = Math.min(MAX_LIVES, state.lives + recovered);
    saveLives();

    if (state.lives >= MAX_LIVES) {
        setRecoveryAt(0);
    } else {
        setRecoveryAt(now + LIFE_RECOVERY_MS);
    }
}

function scheduleRecovery() {
    recoverAvailableLives();
    renderLives();

    if (recoveryTimer) {
        clearInterval(recoveryTimer);
    }

    recoveryTimer = setInterval(() => {
        recoverAvailableLives();
        renderLives();
    }, 1000);
}

function loseLife() {
    recoverAvailableLives();

    if (state.lives <= 0) {
        renderLives();
        return false;
    }

    state.lives--;
    saveLives();

    // Si todavía quedan vidas, comienza el contador para la próxima.
    if (state.lives < MAX_LIVES && !getRecoveryAt()) {
        setRecoveryAt(Date.now() + LIFE_RECOVERY_MS);
    }

    renderLives();
    return true;
}

function restoreAllLives() {
    state.lives = MAX_LIVES;
    saveLives();
    setRecoveryAt(0);
    renderLives();
}

function initializeLivesSystem() {
    recoverAvailableLives();
    scheduleRecovery();

    const devButton = document.getElementById("restoreLivesButton");
    if (devButton) {
        devButton.addEventListener("click", () => {
            restoreAllLives();
        });
    }
}

initializeLivesSystem();
