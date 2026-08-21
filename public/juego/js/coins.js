const MAX_COINS = 999999;
const COINS_STORAGE_KEY = "candyCoins";
const COIN_REWARD_BASE = 50;
const COIN_REWARD_PER_STAR = 25;
const LIFE_COST = 100;

let playerCoins = Number(localStorage.getItem(COINS_STORAGE_KEY));
if (!Number.isFinite(playerCoins) || playerCoins < 0) {
    playerCoins = 0;
}
playerCoins = Math.min(MAX_COINS, Math.floor(playerCoins));

function saveCoins() {
    localStorage.setItem(COINS_STORAGE_KEY, String(playerCoins));
}

function getCoins() {
    return playerCoins;
}

function addCoins(amount) {
    const value = Math.max(0, Math.floor(Number(amount) || 0));
    if (value <= 0) return;

    playerCoins = Math.min(MAX_COINS, playerCoins + value);
    saveCoins();
    renderCoins();
}

function spendCoins(amount) {
    const value = Math.max(0, Math.floor(Number(amount) || 0));
    if (value <= 0 || playerCoins < value) return false;

    playerCoins -= value;
    saveCoins();
    renderCoins();
    return true;
}

function getLevelCoinReward(stars) {
    return COIN_REWARD_BASE + (Math.max(1, Number(stars) || 1) * COIN_REWARD_PER_STAR);
}

function renderCoins() {
    const display = document.getElementById("coinsDisplay");
    if (!display) return;

    display.innerHTML = `
        <span class="coinsIcon" aria-hidden="true">$</span>
        <span class="coinsLabel">Monedas</span>
        <strong>${playerCoins.toLocaleString("es-UY")}</strong>
    `;

    const recoverButton = document.getElementById("buyLifeButton");
    if (recoverButton) {
        const canRecover = state.lives < MAX_LIVES;
        recoverButton.hidden = !canRecover;
        recoverButton.disabled = !canRecover || playerCoins < LIFE_COST;
        recoverButton.innerHTML = playerCoins >= LIFE_COST
            ? `❤️ Recuperar vida · ${LIFE_COST} <span class="coinBadge" aria-hidden="true">$</span>`
            : `🔒 Faltan ${LIFE_COST - playerCoins} <span class="coinBadge" aria-hidden="true">$</span>`;
    }
}

function buyLifeWithCoins() {
    if (state.lives >= MAX_LIVES) {
        renderCoins();
        return false;
    }

    if (!spendCoins(LIFE_COST)) {
        renderCoins();
        return false;
    }

    state.lives = Math.min(MAX_LIVES, state.lives + 1);
    localStorage.setItem("candyLives", String(state.lives));

    if (state.lives >= MAX_LIVES && typeof setRecoveryAt === "function") {
        setRecoveryAt(0);
    }

    if (typeof renderLives === "function") {
        renderLives();
    }

    renderCoins();
    return true;
}

function initializeCoinsSystem() {
    renderCoins();

    const button = document.getElementById("buyLifeButton");
    if (button) {
        button.addEventListener("click", () => {
            initAudio();
            if (buyLifeWithCoins()) {
                playSelectSound();
            }
        });
    }
}

initializeCoinsSystem();

const devCoinsButton = document.getElementById("devCoinsButton");
if (devCoinsButton) {
    devCoinsButton.addEventListener("click", () => {
        addCoins(500);
        playSelectSound();
    });
}
