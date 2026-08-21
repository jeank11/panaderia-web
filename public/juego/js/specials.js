function showComboMessage(matchCount, cascadeNumber, type = "normal") {
    const element = document.getElementById("comboMessage");
    if (!element) return;

    let text;
    if (type === "superRainbow") text = "🌈💥 ¡SUPER ARCOÍRIS!";
    else if (type === "megaRainbow") text = "🌈🌈 ¡MEGA COMBO!";
    else if (type === "specialCombo") text = "💥 ¡SUPER COMBO!";
    else if (type === "rainbow") text = "🌈 ¡ARCOÍRIS!";
    else if (type === "row") text = "✨ ¡PIEZA ESPECIAL!";
    else if (cascadeNumber > 1) text = `¡CASCADA x${cascadeNumber}!`;
    else if (matchCount >= 5) text = "🌈 ¡COMBO DE 5!";
    else if (matchCount === 4) text = "✨ ¡GENIAL!";
    else text = "🥐 ¡BIEN!";

    element.textContent = text;
    element.classList.remove("show");
    void element.offsetWidth;
    element.classList.add("show");
}

async function finishWithCascades() {
    let cascadeMatches = findMatches();
    let cascadeNumber = 1;

    while (cascadeMatches.size > 0) {
        cascadeNumber++;
        showComboMessage(cascadeMatches.size, cascadeNumber);
        playCascadeSound(cascadeNumber);
        highlightMatches(cascadeMatches);
        await sleep(350);

        const result = processMatches(cascadeMatches, []);
        if (result.rainbowCreated) playRainbowCreatedSound();
        else if (result.rowSpecialCreated) playSpecialCreatedSound();

        renderBoard(() => {});
        renderObjectives();
        await sleep(120);
        collapseBoard();
        renderBoard(() => {});
        renderObjectives();
        await sleep(180);
        refillBoard();
        renderBoard(() => {});
        renderObjectives();
        await sleep(100);

        cascadeMatches = findMatches();
    }
}

async function resolveSpecials(activatedCells) {
    const rowsToClear = new Set();

    for (const cell of activatedCells) {
        if (state.specialType[cell.row][cell.col] === 1) rowsToClear.add(cell.row);
    }

    if (rowsToClear.size === 0) return;

    const rowMatches = new Set();
    for (const row of rowsToClear) {
        for (let col = 0; col < COLS; col++) rowMatches.add(`${row},${col}`);
    }

    showComboMessage(rowMatches.size, 1, "row");
    if (window.ParticleFX) ParticleFX.spawnBoardBurst("special", 22);
    playSpecialActivateSound();
    highlightMatches(rowMatches);
    await sleep(350);

    for (const position of rowMatches) {
        const [row, col] = position.split(",").map(Number);
        if (state.board[row][col] !== null && state.board[row][col] !== undefined) {
            if (clearBoardCell(row, col)) {
                state.score += 10;
            }
        }
    }

    renderBoard(() => {});
        renderObjectives();
    await sleep(120);
    collapseBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(180);
    refillBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(100);
    await finishWithCascades();
}

async function resolveRainbow(rainbowCell, targetType) {
    if (targetType === null || targetType === undefined) return;

    const rainbowMatches = new Set();
    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) {
            if (state.board[row][col] === targetType && state.specialType[row][col] !== 2) {
                rainbowMatches.add(`${row},${col}`);
            }
        }
    }

    rainbowMatches.add(`${rainbowCell.row},${rainbowCell.col}`);
    showComboMessage(rainbowMatches.size, 1, "rainbow");
    if (window.ParticleFX) ParticleFX.spawnBoardBurst("rainbow", 28);
    playRainbowActivateSound();
    highlightMatches(rainbowMatches);
    await sleep(400);

    for (const position of rainbowMatches) {
        const [row, col] = position.split(",").map(Number);
        if (state.board[row][col] !== null && state.board[row][col] !== undefined) {
            if (clearBoardCell(row, col)) {
                state.score += 15;
            }
        }
    }

    renderBoard(() => {});
        renderObjectives();
    await sleep(120);
    collapseBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(180);
    refillBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(100);
    await finishWithCascades();
}

async function resolveSpecialRowColumnCombo(rowA, colA, rowB, colB) {
    const comboMatches = new Set();

    for (let col = 0; col < COLS; col++) comboMatches.add(`${rowA},${col}`);
    for (let row = 0; row < ROWS; row++) comboMatches.add(`${row},${colB}`);
    for (let col = 0; col < COLS; col++) comboMatches.add(`${rowB},${col}`);
    for (let row = 0; row < ROWS; row++) comboMatches.add(`${row},${colA}`);

    showComboMessage(comboMatches.size, 1, "specialCombo");
    if (window.ParticleFX) ParticleFX.spawnBoardBurst("special", 30);
    playSpecialComboSound();
    highlightMatches(comboMatches);
    await sleep(450);

    for (const position of comboMatches) {
        const [row, col] = position.split(",").map(Number);
        if (state.board[row][col] !== null && state.board[row][col] !== undefined) {
            if (clearBoardCell(row, col)) {
                state.score += 15;
            }
        }
    }

    renderBoard(() => {});
        renderObjectives();
    await sleep(120);
    collapseBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(180);
    refillBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(120);
    await finishWithCascades();
}

async function resolveRainbowRowCombo(targetType) {
    if (targetType === null || targetType === undefined) return;

    const rowsToClear = new Set();
    const cellsToMark = new Set();

    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) {
            if (state.board[row][col] === targetType) rowsToClear.add(row);
        }
    }

    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) {
            if (state.board[row][col] === targetType || state.specialType[row][col] === 2) {
                cellsToMark.add(`${row},${col}`);
            }
        }
    }

    for (const row of rowsToClear) {
        for (let col = 0; col < COLS; col++) cellsToMark.add(`${row},${col}`);
    }

    showComboMessage(cellsToMark.size, 1, "superRainbow");
    if (window.ParticleFX) ParticleFX.spawnBoardBurst("rainbow", 34);
    playSuperRainbowSound();
    highlightMatches(cellsToMark);
    await sleep(500);

    for (const position of cellsToMark) {
        const [row, col] = position.split(",").map(Number);
        if (state.board[row][col] !== null && state.board[row][col] !== undefined) {
            if (clearBoardCell(row, col)) {
                state.score += 20;
            }
        }
    }

    renderBoard(() => {});
        renderObjectives();
    await sleep(140);
    collapseBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(200);
    refillBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(120);
    await finishWithCascades();
}

async function resolveDoubleRainbow() {
    const allCells = new Set();
    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) allCells.add(`${row},${col}`);
    }

    showComboMessage(allCells.size, 1, "megaRainbow");
    if (window.ParticleFX) ParticleFX.spawnBoardBurst("rainbow", 42);
    playSuperRainbowSound();
    highlightMatches(allCells);
    await sleep(550);

    for (const position of allCells) {
        const [row, col] = position.split(",").map(Number);
        if (state.board[row][col] !== null && state.board[row][col] !== undefined) {
            if (clearBoardCell(row, col)) {
                state.score += 20;
            }
        }
    }

    renderBoard(() => {});
        renderObjectives();
    await sleep(140);
    collapseBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(200);
    refillBoard();
    renderBoard(() => {});
        renderObjectives();
    await sleep(120);
    await finishWithCascades();
}
