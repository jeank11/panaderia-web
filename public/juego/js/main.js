const nicknameModal = document.getElementById("nicknameModal");
const nicknameInput = document.getElementById("nicknameInput");
const nicknameError = document.getElementById("nicknameError");
const saveNicknameButton = document.getElementById("saveNicknameButton");

function sanitizeNickname(value) {
    return value.replace(/\s+/g, " ").trim();
}

function openNicknameModal() {
    if (!nicknameModal) return;

    const current = state.playerName || "";
    nicknameInput.value = current;
    nicknameError.textContent = "";
    nicknameModal.classList.remove("hidden");
    nicknameModal.setAttribute("aria-hidden", "false");

    setTimeout(() => {
        nicknameInput.focus();
        nicknameInput.select();
    }, 0);
}

function closeNicknameModal() {
    if (!nicknameModal) return;
    nicknameModal.classList.add("hidden");
    nicknameModal.setAttribute("aria-hidden", "true");
}

function savePlayerNickname() {
    const nickname = sanitizeNickname(nicknameInput.value);

    if (nickname.length < 3 || nickname.length > 15) {
        nicknameError.textContent = "El apodo debe tener entre 3 y 15 caracteres.";
        nicknameInput.focus();
        return false;
    }

    state.playerName = nickname;
    localStorage.setItem("candyPlayerName", nickname);
    closeNicknameModal();
    return true;
}

if (saveNicknameButton) {
    saveNicknameButton.addEventListener("click", () => {
        if (savePlayerNickname()) {
            initAudio();
            startMusic();
            playSelectSound();
            startScreen.classList.add("hidden");
            levelSelectScreen.classList.remove("hidden");
            renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
        }
    });
}

if (nicknameInput) {
    nicknameInput.addEventListener("keydown", event => {
        if (event.key === "Enter") {
            event.preventDefault();
            saveNicknameButton.click();
        }
    });
}

const startScreen = document.getElementById("startScreen");
const levelSelectScreen = document.getElementById("levelSelectScreen");
const gameScreen = document.getElementById("gameScreen");
const playButton = document.getElementById("playButton");
const backToStartButton = document.getElementById("backToStartButton");
const nextLevelButton = document.getElementById("nextLevelButton");
const retryLevelButton = document.getElementById("retryLevelButton");
const rankingButton = document.getElementById("rankingButton");
const closeRankingButton = document.getElementById("closeRankingButton");
const closeRankingButtonBottom = document.getElementById("closeRankingButtonBottom");

function handleClick(row, col) {
    if (typeof activeBooster !== "undefined") {
        if (activeBooster === "hammer") { useHammerOnCell(row, col); return; }
        if (activeBooster === "bomb") { useBombAtCell(row, col); return; }
        if (activeBooster === "rocket") { useRocketAtCell(row, col); return; }
        if (activeBooster === "swap") {
            if (!canMoveCell(row, col)) return;
            if (!state.selected) { state.selected = {row, col}; renderBoard(); return; }
            const first = state.selected;
            if (first.row === row && first.col === col) { state.selected = null; renderBoard(); return; }
            activateSwapBooster(first, {row, col});
            return;
        }
    }

    if (state.busy || state.levelComplete) return;

    if (state.moves <= 0) {
        document.getElementById("message").textContent = "😔 Se terminaron los movimientos.";
        return;
    }

    if (!canMoveCell(row, col)) {
        state.selected = null;
        playInvalidMoveSound();
        const message = state.boxes[row]?.[col] > 0
            ? "📦 Esa casilla está bloqueada. Rompe la caja para liberar el producto."
            : state.ice[row]?.[col] > 0
                ? "🧊 Esa pieza está congelada. Rompe el hielo para liberarla."
                : "🚫 Esa zona del tablero no tiene casilla.";
        document.getElementById("message").textContent = message;
        renderBoard();
        renderObjectives();
        return;
    }

    if (!state.selected) {
        state.selected = { row, col };
        playSelectSound();
        renderBoard();
        renderObjectives();
        return;
    }

    if (state.selected.row === row && state.selected.col === col) {
        state.selected = null;
        playSelectSound();
        renderBoard();
        renderObjectives();
        return;
    }

    const second = { row, col };

    if (
        Math.abs(state.selected.row - second.row) +
        Math.abs(state.selected.col - second.col) !== 1
    ) {
        state.selected = second;
        playSelectSound();
        renderBoard();
        renderObjectives();
        return;
    }

    const first = {
        row: state.selected.row,
        col: state.selected.col
    };

    if (!canMoveCell(second.row, second.col)) {
        state.selected = { row: second.row, col: second.col };
        playInvalidMoveSound();
        const message = state.boxes[second.row]?.[second.col] > 0
            ? "📦 Esa casilla está bloqueada. Rompe la caja para liberar el producto."
            : state.ice[second.row]?.[second.col] > 0
                ? "🧊 Esa pieza está congelada. Rompe el hielo para liberarla."
                : "🚫 Esa zona del tablero no tiene casilla.";
        document.getElementById("message").textContent = message;
        renderBoard();
        renderObjectives();
        return;
    }

    const firstSpecial = state.specialType[first.row][first.col];
    const secondSpecial = state.specialType[second.row][second.col];

    let rowSpecialPieceType = null;

    if (firstSpecial === 1) {
        rowSpecialPieceType = state.board[first.row][first.col];
    } else if (secondSpecial === 1) {
        rowSpecialPieceType = state.board[second.row][second.col];
    }

    let rainbowTargetType = null;

    if (firstSpecial === 2 && secondSpecial === 0) {
        rainbowTargetType = state.board[second.row][second.col];
    } else if (secondSpecial === 2 && firstSpecial === 0) {
        rainbowTargetType = state.board[first.row][first.col];
    }

    swapPieces(first, second);

    // 🌈 + ✨
    if (
        (firstSpecial === 2 && secondSpecial === 1) ||
        (firstSpecial === 1 && secondSpecial === 2)
    ) {
        state.moves--;
        state.selected = null;
        state.busy = true;
        playValidMoveSound();
        playSuperRainbowSound();
        document.getElementById("message").textContent = "🌈💥 ¡SUPER ARCOÍRIS!";
        renderBoard();
        renderObjectives();
        awaitSpecial(resolveRainbowRowCombo(rowSpecialPieceType));
        return;
    }

    // ✨ + ✨
    if (firstSpecial === 1 && secondSpecial === 1) {
        state.moves--;
        state.selected = null;
        state.busy = true;
        playValidMoveSound();
        playSpecialComboSound();
        document.getElementById("message").textContent = "💥 ¡SUPER COMBO!";
        renderBoard();
        renderObjectives();
        awaitSpecial(resolveSpecialRowColumnCombo(second.row, second.col, first.row, first.col));
        return;
    }

    // 🌈 + 🌈
    if (firstSpecial === 2 && secondSpecial === 2) {
        state.moves--;
        state.selected = null;
        state.busy = true;
        playValidMoveSound();
        playSuperRainbowSound();
        document.getElementById("message").textContent = "🌈🌈 ¡MEGA COMBO!";
        renderBoard();
        renderObjectives();
        awaitSpecial(resolveDoubleRainbow());
        return;
    }

    // 🌈 + normal
    if (firstSpecial === 2 || secondSpecial === 2) {
        state.moves--;
        state.selected = null;
        state.busy = true;
        playValidMoveSound();
        playRainbowActivateSound();
        document.getElementById("message").textContent = "🌈 ¡PIEZA ARCOÍRIS!";
        renderBoard();
        renderObjectives();

        const rainbowCell = firstSpecial === 2
            ? { row: second.row, col: second.col }
            : { row: first.row, col: first.col };

        awaitSpecial(resolveRainbow(rainbowCell, rainbowTargetType));
        return;
    }

    // ✨ + normal
    if (firstSpecial === 1 || secondSpecial === 1) {
        state.moves--;
        state.selected = null;
        state.busy = true;
        playValidMoveSound();
        playSpecialActivateSound();
        document.getElementById("message").textContent = "💥 ¡PIEZA ESPECIAL!";
        renderBoard();
        renderObjectives();

        const activatedCells = firstSpecial === 1
            ? [{ row: second.row, col: second.col }]
            : [{ row: first.row, col: first.col }];

        awaitSpecial(resolveSpecials(activatedCells));
        return;
    }

    // Movimiento normal
    const currentMatches = findMatches();

    if (currentMatches.size === 0) {
        swapPieces(first, second);
        state.selected = null;
        playInvalidMoveSound();
        document.getElementById("message").textContent = "Ese movimiento no forma una combinación.";
        renderBoard();
        renderObjectives();
        return;
    }

    state.moves--;
    state.selected = null;
    state.busy = true;
    playValidMoveSound();
    document.getElementById("message").textContent = "¡Excelente!";
    renderBoard();
        renderObjectives();

    awaitSpecial(resolveBoard({
        preferredCells: [first, second],
        showComboMessage
    }));
}

function awaitSpecial(promise) {
    promise.then(() => {
        state.busy = false;
        if (state.moves <= 0) checkLevelComplete();
        renderBoard();
        renderObjectives();
    }).catch(error => {
        console.error(error);
        state.busy = false;
        renderBoard();
        renderObjectives();
    });
}

state.handleCellClick = handleClick;

playButton.addEventListener("click", () => {
    initAudio();

    if (!state.playerName) {
        openNicknameModal();
        return;
    }

    playSelectSound();
    startScreen.classList.add("hidden");
    levelSelectScreen.classList.remove("hidden");
    renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
});

backToStartButton.addEventListener("click", () => {
    playSelectSound();
    levelSelectScreen.classList.add("hidden");
    startScreen.classList.remove("hidden");
});

if (rankingButton) {
    rankingButton.addEventListener("click", () => {
        initAudio();
        playSelectSound();
        openRankingModal();
    });
}

const devUnlockButton = document.getElementById("devUnlockButton");
if (devUnlockButton) {
    devUnlockButton.addEventListener("click", () => {
        state.unlockedLevel = TOTAL_LEVELS;
        localStorage.setItem("candyUnlockedLevel", String(TOTAL_LEVELS));
        renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
    });
}

if (closeRankingButton) {
    closeRankingButton.addEventListener("click", () => {
        playSelectSound();
        closeRankingModal();
    });
}

if (closeRankingButtonBottom) {
    closeRankingButtonBottom.addEventListener("click", () => {
        playSelectSound();
        closeRankingModal();
    });
}

const rankingModal = document.getElementById("rankingModal");
if (rankingModal) {
    rankingModal.addEventListener("click", event => {
        if (event.target === rankingModal) {
            closeRankingModal();
        }
    });
}

nextLevelButton.addEventListener("click", () => {
    initAudio();
    playSelectSound();

    if (state.level < TOTAL_LEVELS) {
        if (!hasLives()) {
            document.getElementById("levelComplete").style.display = "none";
            gameScreen.classList.add("hidden");
            levelSelectScreen.classList.remove("hidden");
            renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
            return;
        }
        document.getElementById("levelComplete").style.display = "none";
        gameScreen.classList.add("hidden");
        levelSelectScreen.classList.remove("hidden");
        renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
        return;
    }

    document.getElementById("levelComplete").innerHTML = `
        <div class="resultIcon">🏆</div>
        <h2>¡Completaste los 60 niveles!</h2>
        <p>¡Felicitaciones!</p>
        <button id="restartGameButton">Jugar de nuevo</button>
    `;

    document.getElementById("restartGameButton").addEventListener("click", () => {
        localStorage.setItem("candyUnlockedLevel", "1");
        state.unlockedLevel = 1;

        for (let i = 1; i <= TOTAL_LEVELS; i++) {
            localStorage.removeItem(`candyBestScore_${i}`);
        }

        gameScreen.classList.add("hidden");
        levelSelectScreen.classList.remove("hidden");
        renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
    });
});

retryLevelButton.addEventListener("click", () => {
    if (!hasLives()) {
        document.getElementById("levelFailed").style.display = "none";
        gameScreen.classList.add("hidden");
        levelSelectScreen.classList.remove("hidden");
        renderLevelButtons(levelNumber => loadLevel(levelNumber, handleClick));
        return;
    }
    initAudio();
    playSelectSound();
    loadLevel(state.level, handleClick);
});

renderLives();
