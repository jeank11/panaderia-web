let previousBoardSnapshot = null;
let previousSpecialSnapshot = null;
let previousBoxesSnapshot = null;

function sleep(milliseconds) {
    return new Promise(resolve => setTimeout(resolve, milliseconds));
}

function randomPiece() {
    return Math.floor(Math.random() * PIECES.length);
}

function getLevelConfig() {
    return levels[state.level - 1] || {};
}

function createPlayableMask() {
    const shape = getLevelConfig().shape;
    state.playable = Array.from({ length: ROWS }, () => Array(COLS).fill(true));

    if (!shape) return;

    const centerR = (ROWS - 1) / 2;
    const centerC = (COLS - 1) / 2;

    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) {
            let playable = true;
            const edgeR = Math.min(row, ROWS - 1 - row);
            const edgeC = Math.min(col, COLS - 1 - col);

            switch (shape) {
                case "cross":
                    playable = row >= 2 && row <= 5 || col >= 2 && col <= 5;
                    break;
                case "diamond":
                    playable = Math.abs(row - centerR) + Math.abs(col - centerC) <= 3.5;
                    break;
                case "corners":
                    playable = !(edgeR <= 1 && edgeC <= 1);
                    break;
                case "hourglass":
                    playable = (edgeR >= 1 && edgeC >= 1) || (row >= 2 && row <= 5 && col >= 2 && col <= 5);
                    break;
                case "ring":
                    playable = !(row >= 3 && row <= 4 && col >= 3 && col <= 4);
                    break;
                case "bridge":
                    playable = row !== 0 && row !== 7 || (col >= 2 && col <= 5);
                    break;
                case "plus":
                    playable = row >= 2 && row <= 5 || col >= 2 && col <= 5;
                    break;
                case "butterfly":
                    playable = (col <= 2 || col >= 5 || (row >= 2 && row <= 5));
                    break;
                case "stair":
                    playable = col >= Math.max(0, row - 1) && col <= Math.min(COLS - 1, row + 5);
                    break;
                case "fortress":
                    playable = !(row <= 1 && col <= 1) && !(row <= 1 && col >= 6) && !(row >= 6 && col <= 1) && !(row >= 6 && col >= 6);
                    break;
                case "heart":
                    playable = (row <= 2 && (col >= 1 && col <= 2 || col >= 5 && col <= 6)) ||
                        (row >= 2 && col >= 1 && col <= 6) ||
                        (row === 1 && col >= 3 && col <= 4);
                    break;
                case "pyramid":
                    playable = (row === 0 && col >= 3 && col <= 4) ||
                        (row === 1 && col >= 2 && col <= 5) ||
                        (row === 2 && col >= 1 && col <= 6) ||
                        row >= 3;
                    break;
                case "split":
                    playable = !((col === 3 || col === 4) && row >= 1 && row <= 6 && row !== 3 && row !== 4);
                    break;
                case "zigzag":
                    playable = (row % 2 === 0) ? col <= 5 : col >= 2;
                    break;
                case "donut":
                    playable = !((row >= 2 && row <= 5) && (col >= 2 && col <= 5));
                    break;
                default:
                    playable = true;
            }

            state.playable[row][col] = playable;
        }
    }
}

function isPlayableCell(row, col) {
    return !!state.playable[row]?.[col];
}

function getIceObjective() {
    return (getLevelConfig().objectives || []).find(objective => objective.type === "ice") || null;
}

function getIceCountForLevel() {
    return getLevelConfig().iceCount || 0;
}

function getIceHitsForLevel() {
    const objective = getIceObjective();
    return getLevelConfig().iceHits || objective?.hits || 0;
}

function createIceMatrix() {
    state.ice = Array.from({ length: ROWS }, () => Array(COLS).fill(0));
}

function placeIce() {
    const count = getIceCountForLevel();
    const hits = getIceHitsForLevel();
    if (!count || !hits) return;

    const positions = [];
    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) {
            if (isPlayableCell(row, col) && !state.boxes[row][col]) {
                positions.push({ row, col });
            }
        }
    }

    for (let i = positions.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [positions[i], positions[j]] = [positions[j], positions[i]];
    }

    let placed = 0;
    for (const position of positions) {
        if (placed >= count) break;
        const neighbors = [
            [position.row - 1, position.col], [position.row + 1, position.col],
            [position.row, position.col - 1], [position.row, position.col + 1]
        ];
        if (neighbors.some(([r, c]) => state.ice[r]?.[c] > 0 || state.boxes[r]?.[c] > 0)) continue;
        state.ice[position.row][position.col] = hits;
        state.board[position.row][position.col] = null;
        state.specialType[position.row][position.col] = 0;
        placed++;
    }
}

function getBoxObjective() {
    return (levels[state.level - 1].objectives || [])
        .find(objective => objective.type === "box") || null;
}

function getBoxCountForLevel() {
    const objective = getBoxObjective();
    return objective ? objective.count : 0;
}

function getBoxHitsForLevel() {
    const objective = getBoxObjective();
    return objective ? (objective.hits || 1) : 0;
}

function createBoxMatrix() {
    state.boxes = [];
    for (let row = 0; row < ROWS; row++) {
        state.boxes[row] = [];
        for (let col = 0; col < COLS; col++) {
            state.boxes[row][col] = 0;
        }
    }
}

function placeBoxes() {
    const count = getBoxCountForLevel();
    const hits = getBoxHitsForLevel();
    if (!count || !hits) return;

    const positions = [];
    for (let row = 1; row < ROWS - 1; row++) {
        for (let col = 1; col < COLS - 1; col++) {
            if (isPlayableCell(row, col)) positions.push({ row, col });
        }
    }

    // Deterministic shuffle so layouts differ less between reloads but remain stable.
    for (let i = positions.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));
        [positions[i], positions[j]] = [positions[j], positions[i]];
    }

    let placed = 0;
    for (const position of positions) {
        if (placed >= count) break;

        const nearby = [
            [position.row - 1, position.col],
            [position.row + 1, position.col],
            [position.row, position.col - 1],
            [position.row, position.col + 1]
        ];

        // Keep boxes slightly separated so the first hit is readable.
        if (nearby.some(([r, c]) => state.boxes[r]?.[c] > 0)) continue;

        state.boxes[position.row][position.col] = hits;
        state.board[position.row][position.col] = null;
        state.specialType[position.row][position.col] = 0;
        placed++;
    }
}

function createBoard() {
    state.board = [];
    state.specialType = [];
    createPlayableMask();
    createBoxMatrix();
    createIceMatrix();

    for (let row = 0; row < ROWS; row++) {
        state.board[row] = [];
        state.specialType[row] = [];

        for (let col = 0; col < COLS; col++) {
            if (!isPlayableCell(row, col)) {
                state.board[row][col] = null;
                state.specialType[row][col] = 0;
                continue;
            }

            let piece;
            do {
                piece = randomPiece();
            } while (
                (col >= 2 && isPlayableCell(row, col - 1) && isPlayableCell(row, col - 2) && state.board[row][col - 1] === piece && state.board[row][col - 2] === piece) ||
                (row >= 2 && isPlayableCell(row - 1, col) && isPlayableCell(row - 2, col) && state.board[row - 1][col] === piece && state.board[row - 2][col] === piece)
            );
            state.board[row][col] = piece;
            state.specialType[row][col] = 0;
        }
    }

    placeBoxes();
    placeIce();
}

function damageBox(row, col) {
    if (!state.boxes[row] || !state.boxes[row][col]) return false;

    state.boxes[row][col]--;
    if (window.ParticleFX) ParticleFX.spawnBurst(row, col, "box", state.boxes[row][col] > 0 ? 5 : 8);

    if (state.boxes[row][col] <= 0) {
        state.boxes[row][col] = 0;
        registerBoxBroken();
        return true;
    }

    return false;
}

function damageIce(row, col) {
    if (!state.ice[row] || !state.ice[row][col]) return false;

    state.ice[row][col]--;
    if (window.ParticleFX) ParticleFX.spawnBurst(row, col, "ice", state.ice[row][col] > 0 ? 5 : 8);

    if (state.ice[row][col] <= 0) {
        state.ice[row][col] = 0;
        registerIceBroken();
        return true;
    }
    return false;
}

function damageAdjacentIce(row, col) {
    const neighbors = [
        [row - 1, col], [row + 1, col], [row, col - 1], [row, col + 1]
    ];
    for (const [r, c] of neighbors) {
        if (r >= 0 && r < ROWS && c >= 0 && c < COLS) damageIce(r, c);
            playObstacleSound("ice");
    }
}

function damageAdjacentBoxes(row, col) {
    const neighbors = [
        [row - 1, col],
        [row + 1, col],
        [row, col - 1],
        [row, col + 1]
    ];

    for (const [r, c] of neighbors) {
        if (r >= 0 && r < ROWS && c >= 0 && c < COLS) {
            damageBox(r, c);
            playObstacleSound("box");
        }
    }
}

function clearBoardCell(row, col) {
    if (!isPlayableCell(row, col)) return false;

    if (state.boxes[row]?.[col] > 0) {
        const broken = damageBox(row, col);
        return broken;
    }

    const piece = state.board[row][col];
    if (piece === null || piece === undefined) return false;

    registerClearedPiece(piece);
    if (window.ParticleFX) {
        const particleType = state.specialType[row][col] === 2
            ? "rainbow"
            : state.specialType[row][col] === 1
                ? "special"
                : "sweet";
        ParticleFX.spawnBurst(row, col, particleType, state.specialType[row][col] > 0 ? 9 : 5);
    }
    state.board[row][col] = null;
    state.specialType[row][col] = 0;
    damageAdjacentBoxes(row, col);
    damageAdjacentIce(row, col);
    return true;
}

function canMoveCell(row, col) {
    return isPlayableCell(row, col) && !state.boxes[row]?.[col] && !state.ice[row]?.[col];
}

function swapPieces(a, b) {
    const tempPiece = state.board[a.row][a.col];
    state.board[a.row][a.col] = state.board[b.row][b.col];
    state.board[b.row][b.col] = tempPiece;

    const tempSpecial = state.specialType[a.row][a.col];
    state.specialType[a.row][a.col] = state.specialType[b.row][b.col];
    state.specialType[b.row][b.col] = tempSpecial;
}

function renderBoard(handleCellClick = state.handleCellClick) {
    const boardElement = document.getElementById("board");
    boardElement.innerHTML = "";

    for (let row = 0; row < ROWS; row++) {
        for (let col = 0; col < COLS; col++) {
            const cell = document.createElement("div");
            cell.classList.add("cell");
            cell.dataset.row = row;
            cell.dataset.col = col;

            const boxHits = state.boxes[row]?.[col] || 0;
            const piece = state.board[row][col];
            const type = state.specialType[row][col];

            const previousPiece = previousBoardSnapshot?.[row]?.[col];
            const previousSpecial = previousSpecialSnapshot?.[row]?.[col];
            const previousBox = previousBoxesSnapshot?.[row]?.[col] || 0;

            const pieceChanged =
                previousBoardSnapshot !== null &&
                (previousPiece !== piece ||
                 previousSpecial !== type ||
                 previousBox !== boxHits);

            if (pieceChanged && boxHits === 0) {
                cell.classList.add("pieceChange");
            }

            if (!isPlayableCell(row, col)) {
                cell.classList.add("voidCell");
                cell.textContent = "";
                cell.title = "Zona sin casilla";
            } else if (boxHits > 0) {
                cell.classList.add("boxCell");

                // Caja de 1 golpe: intacta.
                // Caja de 2 golpes: cambia visualmente después
                // del primer golpe para que el jugador sepa que está dañada.
                if (boxHits === 1) {
                    cell.textContent = "📦";
                    cell.title = "Caja: 1 golpe restante";
                } else {
                    cell.textContent = "📦💥";
                    cell.title = `Caja dañada: ${boxHits} golpes restantes`;
                    cell.style.filter = "grayscale(0.25) brightness(0.92)";
                }
            } else if (state.ice[row]?.[col] > 0) {
                cell.classList.add("iceCell");
                if (state.ice[row][col] > 1) cell.classList.add("twoLayers");
                cell.textContent = "🧊";
                cell.title = `Hielo: ${state.ice[row][col]} capa(s) restantes`;
            } else if (piece !== null && piece !== undefined) {
                if (type === 2) {
                    cell.classList.add("rainbowPiece");
                    cell.innerHTML = `<span class="pieceEmoji">🌈</span>`;
                    cell.title = "Pieza arcoíris";
                } else {
                    if (type === 1) {
                        cell.classList.add("specialPiece");
                        cell.innerHTML = `<span class="specialComboVisual"><span class="pieceEmoji">${PIECES[piece]}</span><span class="specialSpark">✨</span></span>`;
                        cell.title = "Pieza especial: elimina una fila";
                    } else {
                        cell.innerHTML = `<span class="pieceEmoji">${PIECES[piece]}</span>`;
                    }
                }
            }

            if (state.selected && state.selected.row === row && state.selected.col === col) {
                cell.classList.add("selected");
            }

            cell.addEventListener("click", () => handleCellClick(row, col));
            boardElement.appendChild(cell);
        }
    }

    document.getElementById("score").textContent = state.score;
    document.getElementById("moves").textContent = state.moves;
    document.getElementById("target").textContent = state.targetScore;

    previousBoardSnapshot = state.board.map(row => [...row]);
    previousSpecialSnapshot = state.specialType.map(row => [...row]);
    previousBoxesSnapshot = state.boxes.map(row => [...row]);
}

function findMatchGroups() {
    const groups = [];

    for (let row = 0; row < ROWS; row++) {
        let start = 0;
        while (start < COLS) {
            const piece = state.board[row][start];
            if (!isPlayableCell(row, start) || state.ice[row]?.[start] || piece === null || piece === undefined || state.specialType[row][start] !== 0 || state.boxes[row]?.[start]) {
                start++;
                continue;
            }

            let end = start + 1;
            while (
                end < COLS &&
                isPlayableCell(row, end) && !state.ice[row]?.[end] &&
                state.board[row][end] === piece &&
                state.specialType[row][end] === 0 &&
                !state.boxes[row]?.[end]
            ) end++;

            if (end - start >= 3) {
                const group = [];
                for (let col = start; col < end; col++) group.push({ row, col });
                groups.push(group);
            }
            start = end;
        }
    }

    for (let col = 0; col < COLS; col++) {
        let start = 0;
        while (start < ROWS) {
            const piece = state.board[start][col];
            if (!isPlayableCell(start, col) || state.ice[start]?.[col] || piece === null || piece === undefined || state.specialType[start][col] !== 0 || state.boxes[start]?.[col]) {
                start++;
                continue;
            }

            let end = start + 1;
            while (
                end < ROWS &&
                isPlayableCell(end, col) && !state.ice[end]?.[col] &&
                state.board[end][col] === piece &&
                state.specialType[end][col] === 0 &&
                !state.boxes[end]?.[col]
            ) end++;

            if (end - start >= 3) {
                const group = [];
                for (let row = start; row < end; row++) group.push({ row, col });
                groups.push(group);
            }
            start = end;
        }
    }

    return groups;
}

function findMatches() {
    const result = new Set();
    for (const group of findMatchGroups()) {
        for (const cell of group) result.add(`${cell.row},${cell.col}`);
    }
    return result;
}

function chooseSpecialCell(group, preferredCells) {
    for (const preferred of preferredCells) {
        const found = group.some(cell => cell.row === preferred.row && cell.col === preferred.col);
        if (found) return { row: preferred.row, col: preferred.col };
    }
    const last = group[group.length - 1];
    return { row: last.row, col: last.col };
}

function processMatches(currentMatches, preferredCells) {
    const groups = findMatchGroups();
    const cellsToRemove = new Set();
    const specialsToCreate = [];

    for (const group of groups) {
        if (group.length < 5) continue;
        const rainbowCell = chooseSpecialCell(group, preferredCells);
        specialsToCreate.push({ row: rainbowCell.row, col: rainbowCell.col, type: 2 });
        for (const cell of group) {
            if (cell.row === rainbowCell.row && cell.col === rainbowCell.col) continue;
            cellsToRemove.add(`${cell.row},${cell.col}`);
        }
    }

    for (const group of groups) {
        if (group.length !== 4) continue;
        const specialCell = chooseSpecialCell(group, preferredCells);
        const pieceType = state.board[specialCell.row][specialCell.col];
        if (pieceType === null || pieceType === undefined) continue;

        specialsToCreate.push({
            row: specialCell.row,
            col: specialCell.col,
            type: 1,
            piece: pieceType
        });

        for (const cell of group) {
            if (cell.row === specialCell.row && cell.col === specialCell.col) continue;
            cellsToRemove.add(`${cell.row},${cell.col}`);
        }
    }

    for (const position of currentMatches) {
        const [row, col] = position.split(",").map(Number);
        const protectedCell = specialsToCreate.some(item => item.row === row && item.col === col);
        if (!protectedCell) cellsToRemove.add(`${row},${col}`);
    }

    let removedCount = 0;

    for (const position of cellsToRemove) {
        const [row, col] = position.split(",").map(Number);
        if (clearBoardCell(row, col)) {
            removedCount++;
        }
    }

    let rainbowCreated = false;
    let rowSpecialCreated = false;

    for (const item of specialsToCreate) {
        if (item.type === 2) {
            state.board[item.row][item.col] = randomPiece();
            state.specialType[item.row][item.col] = 2;
            registerSpecialCreated(2);
            rainbowCreated = true;
        } else {
            state.board[item.row][item.col] = item.piece;
            state.specialType[item.row][item.col] = 1;
            registerSpecialCreated(1);
            rowSpecialCreated = true;
        }
    }

    state.score += removedCount * 10;
    if (rowSpecialCreated) state.score += 20;
    if (rainbowCreated) state.score += 50;

    return { removedCount, rowSpecialCreated, rainbowCreated };
}

function highlightMatches(currentMatches) {
    const cells = document.querySelectorAll(".cell");
    for (const position of currentMatches) {
        const [row, col] = position.split(",").map(Number);
        const index = row * COLS + col;
        if (cells[index]) cells[index].classList.add("matching");
    }
}

function collapseBoard() {
    for (let col = 0; col < COLS; col++) {
        let segmentBottom = ROWS - 1;

        for (let row = ROWS - 1; row >= -1; row--) {
            const isBoundary = row < 0 || !isPlayableCell(row, col) || state.boxes[row]?.[col] > 0 || state.ice[row]?.[col] > 0;

            if (!isBoundary) continue;

            const segmentTop = row + 1;
            let writeRow = segmentBottom;

            for (let currentRow = segmentBottom; currentRow >= segmentTop; currentRow--) {
                if (state.board[currentRow][col] !== null && state.board[currentRow][col] !== undefined) {
                    state.board[writeRow][col] = state.board[currentRow][col];
                    state.specialType[writeRow][col] = state.specialType[currentRow][col];

                    if (writeRow !== currentRow) {
                        state.board[currentRow][col] = null;
                        state.specialType[currentRow][col] = 0;
                    }

                    writeRow--;
                }
            }

            while (writeRow >= segmentTop) {
                state.board[writeRow][col] = null;
                state.specialType[writeRow][col] = 0;
                writeRow--;
            }

            // The obstacle cell itself remains empty underneath the box.
            if (row >= 0 && isPlayableCell(row, col)) {
                state.board[row][col] = null;
                state.specialType[row][col] = 0;
            }

            segmentBottom = row - 1;
        }
    }
}

function refillBoard() {
    for (let col = 0; col < COLS; col++) {
        let segmentBottom = ROWS - 1;

        for (let row = ROWS - 1; row >= -1; row--) {
            const isBoundary = row < 0 || !isPlayableCell(row, col) || state.boxes[row]?.[col] > 0 || state.ice[row]?.[col] > 0;

            if (!isBoundary) continue;

            const segmentTop = row + 1;

            for (let currentRow = segmentTop; currentRow <= segmentBottom; currentRow++) {
                if (isPlayableCell(currentRow, col) && (state.board[currentRow][col] === null || state.board[currentRow][col] === undefined)) {
                    state.board[currentRow][col] = randomPiece();
                    state.specialType[currentRow][col] = 0;
                }
            }

            if (row >= 0 && isPlayableCell(row, col)) {
                state.board[row][col] = null;
                state.specialType[row][col] = 0;
            }

            segmentBottom = row - 1;
        }
    }
}

async function resolveBoard({ preferredCells = [], showComboMessage }) {
    let currentMatches = findMatches();
    let cascadeNumber = 0;

    while (currentMatches.size > 0) {
        cascadeNumber++;

        const type =
            currentMatches.size >= 5
                ? "rainbow"
                : currentMatches.size === 4
                    ? "row"
                    : "normal";

        showComboMessage(
            currentMatches.size,
            cascadeNumber,
            type
        );

        if (cascadeNumber > 1) {
            playCascadeSound(cascadeNumber);
        } else {
            playMatchSound();
        }

        highlightMatches(currentMatches);

        await sleep(350);

        const result = processMatches(
            currentMatches,
            preferredCells
        );

        preferredCells = [];

        if (result.rainbowCreated) {
            playRainbowCreatedSound();
        } else if (result.rowSpecialCreated) {
            playSpecialCreatedSound();
        }

        renderBoard();
        if (typeof renderObjectives === "function") {
            renderObjectives();
        }

        await sleep(120);

        collapseBoard();

        renderBoard();
        if (typeof renderObjectives === "function") {
            renderObjectives();
        }

        await sleep(180);

        refillBoard();

        renderBoard();
        if (typeof renderObjectives === "function") {
            renderObjectives();
        }

        await sleep(100);

        currentMatches = findMatches();
    }
}


