const state = {
    playerName: localStorage.getItem("candyPlayerName") || "",
    lives: undefined,
    unlockedLevel: Number(localStorage.getItem("candyUnlockedLevel")) || 1,
    board: [],
    specialType: [],
    boxes: [],
    ice: [],
    playable: [],
    selected: null,
    score: 0,
    moves: 30,
    level: 1,
    targetScore: 500,
    objectives: [],
    objectiveProgress: {
        cleared: {},
        specialCreated: 0,
        rainbowCreated: 0,
        boxesBroken: 0,
        iceBroken: 0
    },
    busy: false,
    levelComplete: false,
    handleCellClick: null,
    lifeLostForCurrentAttempt: false
};

function resetObjectiveProgress() {
    state.objectiveProgress = {
        cleared: {},
        specialCreated: 0,
        rainbowCreated: 0,
        boxesBroken: 0,
        iceBroken: 0
    };
}

function registerClearedPiece(pieceType) {
    if (pieceType === null || pieceType === undefined) return;
    state.objectiveProgress.cleared[pieceType] =
        (state.objectiveProgress.cleared[pieceType] || 0) + 1;
}

function registerSpecialCreated(type) {
    if (type === 2) {
        state.objectiveProgress.rainbowCreated++;
    } else if (type === 1) {
        state.objectiveProgress.specialCreated++;
    }
}

function registerIceBroken() {
    state.objectiveProgress.iceBroken =
        (state.objectiveProgress.iceBroken || 0) + 1;
}

function registerBoxBroken() {
    state.objectiveProgress.boxesBroken =
        (state.objectiveProgress.boxesBroken || 0) + 1;
}
