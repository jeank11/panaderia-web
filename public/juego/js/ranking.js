const RANKING_STORAGE_KEY = "candyLocalRanking";

function loadLocalRanking() {
    try {
        const saved = JSON.parse(localStorage.getItem(RANKING_STORAGE_KEY));
        return Array.isArray(saved) ? saved : [];
    } catch (error) {
        console.warn("No se pudo cargar el ranking local:", error);
        return [];
    }
}

function saveLocalRanking(ranking) {
    localStorage.setItem(RANKING_STORAGE_KEY, JSON.stringify(ranking));
}

function recordRankingScore(playerName, levelNumber, scoreValue, stars) {
    if (!playerName) return;

    const ranking = loadLocalRanking();
    let entry = ranking.find(item => item.name === playerName);

    if (!entry) {
        entry = {
            name: playerName,
            scoresByLevel: {},
            starsByLevel: {},
            totalScore: 0,
            levelsCompleted: 0,
            totalStars: 0
        };
        ranking.push(entry);
    }

    const levelKey = String(levelNumber);
    const oldScore = Number(entry.scoresByLevel[levelKey]) || 0;
    const oldStars = Number(entry.starsByLevel[levelKey]) || 0;

    if (scoreValue <= oldScore && stars <= oldStars) {
        // Still try to sync in case the online profile is not current.
        void saveOnlineRankingScore(playerName, levelNumber, scoreValue, stars);
        return;
    }

    entry.scoresByLevel[levelKey] = Math.max(oldScore, scoreValue);
    entry.starsByLevel[levelKey] = Math.max(oldStars, stars);

    entry.totalScore = Object.values(entry.scoresByLevel)
        .reduce((total, value) => total + Number(value || 0), 0);

    entry.levelsCompleted = Object.keys(entry.scoresByLevel).length;

    entry.totalStars = Object.values(entry.starsByLevel)
        .reduce((total, value) => total + Number(value || 0), 0);

    entry.bestLevel = Math.max(
        0,
        ...Object.keys(entry.scoresByLevel).map(Number)
    );

    saveLocalRanking(ranking);
    void saveOnlineRankingScore(playerName, levelNumber, scoreValue, stars);
}

function getSortedLocalRanking() {
    return loadLocalRanking().sort((a, b) => {
        if (b.totalScore !== a.totalScore) return b.totalScore - a.totalScore;
        if (b.bestLevel !== a.bestLevel) return b.bestLevel - a.bestLevel;
        return b.totalStars - a.totalStars;
    });
}

function getPlayerRanking(name) {
    const ranking = getSortedLocalRanking();
    const index = ranking.findIndex(item => item.name === name);

    if (index === -1) return { position: 0, entry: null };
    return { position: index + 1, entry: ranking[index] };
}

function formatRankingScore(value) {
    return Number(value || 0).toLocaleString("es-UY");
}

function normalizeOnlineEntry(entry) {
    return {
        name: entry.name || "Jugador",
        totalScore: Number(entry.total_score || 0),
        totalStars: Number(entry.total_stars || 0),
        bestLevel: Number(entry.best_level || 0),
        levelsCompleted: Number(entry.levels_completed || 0),
        userId: entry.user_id || ""
    };
}

function getPlayerPositionFromOnlineRanking(ranking) {
    const index = ranking.findIndex(entry => entry.userId === window.__candyOnlineUserId);
    return index >= 0 ? index + 1 : 0;
}

async function renderRanking() {
    const list = document.getElementById("rankingList");
    const playerInfo = document.getElementById("rankingPlayerInfo");
    const subtitle = document.querySelector(".rankingSubtitle");

    if (!list || !playerInfo) return;

    list.innerHTML = `<div class="rankingEmpty">⏳ Cargando ranking...</div>`;

    const online = await loadOnlineRanking();

    if (online) {
        const ranking = online.map(normalizeOnlineEntry);
        if (subtitle) subtitle.textContent = `Ranking online · ${ranking.length} jugadores visibles.`;

        if (!ranking.length) {
            list.innerHTML = `
                <div class="rankingEmpty">
                    🥐 Todavía no hay puntuaciones online.
                </div>
            `;
            playerInfo.innerHTML = `
                <strong>Tu posición: —</strong>
                <span>Completa un nivel para aparecer en el ranking.</span>
            `;
            return;
        }

        list.innerHTML = ranking.slice(0, 20).map((entry, index) => {
            const position = index + 1;
            const medal = position === 1 ? "🥇" : position === 2 ? "🥈" : position === 3 ? "🥉" : `${position}.`;
            const isCurrent = entry.userId === window.__candyOnlineUserId;
            return `
                <div class="rankingRow ${isCurrent ? "currentPlayer" : ""}">
                    <span class="rankingPosition">${medal}</span>
                    <span class="rankingName">${escapeRankingHtml(entry.name)}</span>
                    <span class="rankingLevel">Nvl. ${entry.bestLevel}</span>
                    <span class="rankingStars">⭐ ${entry.totalStars}</span>
                    <strong class="rankingScore">${formatRankingScore(entry.totalScore)}</strong>
                </div>
            `;
        }).join("");

        const currentIndex = ranking.findIndex(entry => entry.userId === window.__candyOnlineUserId);
        if (currentIndex >= 0) {
            const current = ranking[currentIndex];
            playerInfo.innerHTML = `
                <strong>Tu posición: #${currentIndex + 1}</strong>
                <span>${escapeRankingHtml(current.name)} · ${formatRankingScore(current.totalScore)} puntos</span>
            `;
        } else {
            playerInfo.innerHTML = `
                <strong>Tu posición: —</strong>
                <span>Completa un nivel para aparecer en el ranking.</span>
            `;
        }
        return;
    }

    if (subtitle) {
        subtitle.textContent = isOnlineRankingConfigured()
            ? "Ranking online no disponible ahora · mostrando datos locales."
            : "Ranking local de respaldo · configura Supabase para activar el ranking online.";
    }

    const ranking = getSortedLocalRanking();
    const playerRanking = getPlayerRanking(state.playerName);

    if (ranking.length === 0) {
        list.innerHTML = `
            <div class="rankingEmpty">
                🥐 Todavía no hay puntuaciones registradas.
            </div>
        `;
        playerInfo.innerHTML = "";
        return;
    }

    list.innerHTML = ranking.slice(0, 20).map((entry, index) => {
        const position = index + 1;
        const medal = position === 1 ? "🥇" : position === 2 ? "🥈" : position === 3 ? "🥉" : `${position}.`;
        const isCurrentPlayer = entry.name === state.playerName;
        return `
            <div class="rankingRow ${isCurrentPlayer ? "currentPlayer" : ""}">
                <span class="rankingPosition">${medal}</span>
                <span class="rankingName">${escapeRankingHtml(entry.name)}</span>
                <span class="rankingLevel">Nvl. ${entry.bestLevel || 0}</span>
                <span class="rankingStars">⭐ ${entry.totalStars || 0}</span>
                <strong class="rankingScore">${formatRankingScore(entry.totalScore)}</strong>
            </div>
        `;
    }).join("");

    playerInfo.innerHTML = playerRanking.entry
        ? `<strong>Tu posición: #${playerRanking.position}</strong><span>${escapeRankingHtml(state.playerName)} · ${formatRankingScore(playerRanking.entry.totalScore)} puntos</span>`
        : `<strong>Tu posición: —</strong><span>Completa un nivel para aparecer en el ranking.</span>`;
}

function escapeRankingHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

async function openRankingModal() {
    const modal = document.getElementById("rankingModal");
    if (!modal) return;

    await renderRanking();
    modal.classList.remove("hidden");
    modal.setAttribute("aria-hidden", "false");
}

function closeRankingModal() {
    const modal = document.getElementById("rankingModal");
    if (!modal) return;

    modal.classList.add("hidden");
    modal.setAttribute("aria-hidden", "true");
}

void initOnlineRanking().then(() => {
    window.__candyOnlineUserId = onlineRankingUser?.id || "";
});
