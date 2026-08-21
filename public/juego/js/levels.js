function getElement(id) {
    return document.getElementById(id);
}

function getBestScore(levelNumber) {
    return Number(localStorage.getItem(`candyBestScore_${levelNumber}`)) || 0;
}

function saveBestScore(levelNumber, newScore) {
    const oldScore = getBestScore(levelNumber);

    if (newScore > oldScore) {
        localStorage.setItem(`candyBestScore_${levelNumber}`, newScore);
        return newScore;
    }

    return oldScore;
}

function calculateStars(levelNumber, finalScore) {
    const target = levels[levelNumber - 1].target;

    if (finalScore >= target * 1.75) return 3;
    if (finalScore >= target * 1.25) return 2;
    return 1;
}

function starsText(stars) {
    return "⭐".repeat(stars) + "☆".repeat(3 - stars);
}

function getCurrentObjectives() {
    return levels[state.level - 1].objectives || [];
}

function getObjectiveProgress(objective) {
    if (objective.type === "clear") {
        return state.objectiveProgress.cleared[objective.piece] || 0;
    }

    if (objective.type === "special") {
        return state.objectiveProgress.specialCreated;
    }

    if (objective.type === "rainbow") {
        return state.objectiveProgress.rainbowCreated;
    }

    if (objective.type === "box") {
        return state.objectiveProgress.boxesBroken || 0;
    }

    if (objective.type === "ice") {
        return state.objectiveProgress.iceBroken || 0;
    }

    return 0;
}

function isObjectiveComplete(objective) {
    return getObjectiveProgress(objective) >= objective.count;
}

function areObjectivesComplete() {
    return getCurrentObjectives().every(isObjectiveComplete);
}

function objectiveText(objective) {
    const current = Math.min(
        getObjectiveProgress(objective),
        objective.count
    );

    if (objective.type === "clear") {
        return `${PIECES[objective.piece]} Eliminar ${current}/${objective.count} ${PIECE_NAMES[objective.piece]}`;
    }

    if (objective.type === "special") {
        return `✨ Crear ${current}/${objective.count} piezas especiales`;
    }

    if (objective.type === "rainbow") {
        return `🌈 Crear ${current}/${objective.count} arcoíris`;
    }

    if (objective.type === "box") {
        const hits = objective.hits === 2 ? " (2 golpes)" : "";
        return `📦 Romper ${current}/${objective.count} cajas${hits}`;
    }

    if (objective.type === "ice") {
        const hits = objective.hits === 2 ? " (2 capas)" : "";
        return `🧊 Romper ${current}/${objective.count} hielos${hits}`;
    }

    return "";
}

function renderObjectives() {
    const element = getElement("objectives");
    if (!element) return;

    const objectives = getCurrentObjectives();

    if (objectives.length === 0) {
        element.style.display = "none";
        element.innerHTML = "";
        return;
    }

    element.style.display = "block";
    element.innerHTML = `
        <div class="objectivesTitle">🎯 Objetivos especiales</div>
        ${objectives.map(objective => `
            <div class="objectiveItem ${isObjectiveComplete(objective) ? "completed" : ""}">
                <span>${isObjectiveComplete(objective) ? "✅" : "⬜"}</span>
                <span>${objectiveText(objective)}</span>
            </div>
        `).join("")}
    `;
}

function loadLevel(newLevel, handleCellClick) {
    if (newLevel < 1 || newLevel > TOTAL_LEVELS) return;

    state.level = newLevel;
    state.targetScore = levels[state.level - 1].target;
    state.moves = levels[state.level - 1].moves;
    state.objectives = levels[state.level - 1].objectives || [];
    state.score = 0;
    state.selected = null;
    state.busy = false;
    state.levelComplete = false;
    state.lifeLostForCurrentAttempt = false;

    resetObjectiveProgress();
    renderLives();

    getElement("levelTitle").textContent = `Nivel ${state.level}`;
    getElement("target").textContent = state.targetScore;
    getElement("levelComplete").style.display = "none";
    getElement("levelFailed").style.display = "none";
    getElement("completionStars").textContent = "☆☆☆";
    getElement("completionScore").textContent = "Puntuación: 0";
    getElement("completionBest").textContent = `Récord: ${getBestScore(state.level)}`;
    getElement("message").textContent = objectives.length > 0
        ? "¡Cumple los objetivos y alcanza el puntaje!"
        : "¡Combina 3 productos iguales!";

    createBoard();
    renderBoard(handleCellClick);
    renderObjectives();
}

function getMapZone(levelNumber) {
    if (levelNumber <= 10) return { key: "bakery", name: "Zona 1 · La Panadería", icon: "🥐" };
    if (levelNumber <= 20) return { key: "boxes", name: "Zona 2 · El Almacén", icon: "📦" };
    if (levelNumber <= 30) return { key: "ice", name: "Zona 3 · Cámara Fría", icon: "🧊" };
    if (levelNumber <= 40) return { key: "frozenWorkshop", name: "Zona 4 · Taller Helado", icon: "❄️" };
    if (levelNumber <= 50) return { key: "challenge", name: "Zona 5 · Gran Desafío", icon: "🔥" };
    return { key: "finale", name: "Zona 6 · Gran Panadería", icon: "🏆" };
}

function renderLevelButtons(onSelect) {
    const container = getElement("levelButtons");
    container.innerHTML = "";
    renderLives();

    const noLives = !hasLives();

    if (noLives) {
        const warning = document.createElement("div");
        warning.className = "livesWarning";
        warning.innerHTML = "💔 <strong>Te quedaste sin vidas.</strong><span>Completa o recarga vidas para volver a jugar.</span>";
        container.appendChild(warning);
    }

    let currentZone = null;

    for (let i = 1; i <= TOTAL_LEVELS; i++) {
        const zone = getMapZone(i);

        if (zone.key !== currentZone) {
            currentZone = zone.key;
            const banner = document.createElement("div");
            banner.className = `mapZoneBanner zone-${zone.key}`;
            banner.innerHTML = `<span class="zoneIcon">${zone.icon}</span><div><strong>${zone.name}</strong><span class="zoneSubtitle">Niveles ${i}-${Math.min(i + 3, TOTAL_LEVELS)}</span></div>`;
            container.appendChild(banner);
        }
        const unlocked = i <= state.unlockedLevel;
        const bestScore = getBestScore(i);
        const bestStars = bestScore > 0 ? calculateStars(i, bestScore) : 0;
        const stars = bestStars > 0 ? starsText(bestStars) : "☆☆☆";
        const objectiveCount = (levels[i - 1].objectives || []).length;
        const isCurrent = i === state.level;

        const node = document.createElement("div");
        node.className = `mapNode zone-node-${zone.key} ${unlocked ? "unlocked" : "locked"} ${isCurrent ? "current" : ""}`;
        node.dataset.zone = zone.key;
        node.dataset.level = i;

        const button = document.createElement("button");
        button.type = "button";
        button.classList.add("levelButton");

        if (unlocked && !noLives) {
            button.innerHTML = `
                <span class="mapCheckpoint">${isCurrent ? "🥐" : "🍪"}</span>
                <span class="levelNumber">Nivel ${i}</span>
                <span class="levelStars">${stars}</span>
                <span class="bestScore">${bestScore > 0 ? `🏆 ${bestScore}` : "Sin récord"}</span>
                ${objectiveCount > 0 ? `<span class="objectiveBadge">🎯 ${objectiveCount}</span>` : ""}
            `;

            button.addEventListener("click", () => {
                playSelectSound();
                document.getElementById("levelSelectScreen").classList.add("hidden");
                document.getElementById("gameScreen").classList.remove("hidden");
                onSelect(i);
            });
        } else {
            button.disabled = true;
            button.innerHTML = `
                <span class="mapCheckpoint">🔒</span>
                <span class="levelNumber">Nivel ${i}</span>
                <span class="levelStars">☆☆☆</span>
                <span class="bestScore">Bloqueado</span>
            `;
        }

        node.appendChild(button);
        container.appendChild(node);
    }
}

function checkLevelComplete() {
    const objectivesDone = areObjectivesComplete();

    if (state.moves > 0) {
        if (state.score >= state.targetScore && objectivesDone) {
            getElement("message").textContent =
                "🎯 ¡Objetivos cumplidos! ¡Seguí jugando para conseguir más estrellas!";
        } else if (state.score >= state.targetScore && !objectivesDone) {
            getElement("message").textContent =
                "🎯 ¡Puntaje conseguido! Todavía faltan objetivos especiales.";
        }
        return;
    }

    if (state.score >= state.targetScore && objectivesDone) {
        state.levelComplete = true;
        state.busy = true;

        const bestBefore = getBestScore(state.level);
        const bestScore = saveBestScore(state.level, state.score);
        const stars = calculateStars(state.level, state.score);

        const coinReward = getLevelCoinReward(stars);
        addCoins(coinReward);

        recordRankingScore(
            state.playerName,
            state.level,
            state.score,
            stars
        );

        if (state.level < TOTAL_LEVELS && state.level + 1 > state.unlockedLevel) {
            state.unlockedLevel = state.level + 1;
            localStorage.setItem("candyUnlockedLevel", state.unlockedLevel);
        }

        getElement("levelComplete").style.display = "block";
        getElement("levelFailed").style.display = "none";
        getElement("completionStars").textContent = starsText(stars);
        getElement("completionScore").textContent = `Puntuación: ${state.score}`;
        getElement("completionBest").textContent = state.score > bestBefore
            ? `🏆 Nuevo récord: ${bestScore}`
            : `Récord: ${bestScore}`;
        getElement("message").textContent = `🎉 ¡Nivel ${state.level} completado! +${coinReward} 🪙`;
        playWinSound();
    } else {
        state.levelComplete = true;
        state.busy = true;

        if (!state.lifeLostForCurrentAttempt) {
            loseLife();
            state.lifeLostForCurrentAttempt = true;
        }

        getElement("levelComplete").style.display = "none";
        getElement("levelFailed").style.display = "block";
        getElement("failedScore").textContent = `Puntuación: ${state.score}`;
        getElement("failedTarget").textContent = objectivesDone
            ? `Objetivo de puntuación: ${state.targetScore}`
            : `Puntuación: ${state.score} / ${state.targetScore} y faltan objetivos especiales.`;
        getElement("message").textContent = state.lives <= 0
            ? "💔 Te quedaste sin vidas. No hay más intentos disponibles."
            : (state.score < state.targetScore
                ? "😔 Te quedaste sin movimientos y no alcanzaste el puntaje."
                : "😔 Te quedaste sin movimientos y faltaron objetivos.");
        playLoseSound();
    }
}
