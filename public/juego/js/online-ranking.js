const ONLINE_RANKING_TABLE = "player_rankings";
let supabaseClient = null;
let onlineRankingUser = null;
let onlineRankingReady = false;
let onlineRankingInitPromise = null;

function isOnlineRankingConfigured() {
    const config = window.CANDY_SUPABASE_CONFIG;
    if (!config?.url || !config?.key) return false;
    if (config.url.includes("TU-PROYECTO") || config.key.includes("TU-PUBLISHABLE-KEY")) return false;
    return true;
}

function getOnlineRankingStatus() {
    if (!isOnlineRankingConfigured()) return "not_configured";
    if (!onlineRankingReady) return "connecting";
    return "online";
}

async function initOnlineRanking() {
    if (onlineRankingInitPromise) return onlineRankingInitPromise;

    onlineRankingInitPromise = (async () => {
        if (!isOnlineRankingConfigured() || typeof window.supabase === "undefined") {
            return false;
        }

        try {
            const config = window.CANDY_SUPABASE_CONFIG;
            supabaseClient = window.supabase.createClient(config.url, config.key, {
                auth: {
                    persistSession: true,
                    autoRefreshToken: true,
                    detectSessionInUrl: false
                }
            });

            const { data: sessionData } = await supabaseClient.auth.getSession();
            if (sessionData?.session?.user) {
                onlineRankingUser = sessionData.session.user;
                onlineRankingReady = true;
                return true;
            }

            const { data, error } = await supabaseClient.auth.signInAnonymously();
            if (error) throw error;

            onlineRankingUser = data?.user || data?.session?.user || null;
            onlineRankingReady = Boolean(onlineRankingUser);
            return onlineRankingReady;
        } catch (error) {
            console.warn("Ranking online no disponible:", error);
            supabaseClient = null;
            onlineRankingUser = null;
            onlineRankingReady = false;
            return false;
        }
    })();

    return onlineRankingInitPromise;
}

function normalizeNicknameForOnline(name) {
    return String(name || "Jugador")
        .trim()
        .replace(/\s+/g, " ")
        .slice(0, 15);
}

async function saveOnlineRankingScore(playerName, levelNumber, scoreValue, stars) {
    if (!isOnlineRankingReady()) {
        await initOnlineRanking();
    }

    if (!isOnlineRankingReady() || !onlineRankingUser) return false;

    try {
        const userId = onlineRankingUser.id;
        const { data: current, error: readError } = await supabaseClient
            .from(ONLINE_RANKING_TABLE)
            .select("user_id,name,total_score,total_stars,best_level,levels_completed,scores_by_level,stars_by_level")
            .eq("user_id", userId)
            .maybeSingle();

        if (readError) throw readError;

        const scoresByLevel = {
            ...(current?.scores_by_level || {})
        };
        const starsByLevel = {
            ...(current?.stars_by_level || {})
        };

        const key = String(levelNumber);
        const oldScore = Number(scoresByLevel[key] || 0);
        const oldStars = Number(starsByLevel[key] || 0);

        scoresByLevel[key] = Math.max(oldScore, Number(scoreValue) || 0);
        starsByLevel[key] = Math.max(oldStars, Number(stars) || 0);

        const totalScore = Object.values(scoresByLevel)
            .reduce((sum, value) => sum + Number(value || 0), 0);
        const totalStars = Object.values(starsByLevel)
            .reduce((sum, value) => sum + Number(value || 0), 0);
        const bestLevel = Math.max(0, ...Object.keys(scoresByLevel).map(Number));
        const levelsCompleted = Object.keys(scoresByLevel).length;

        const payload = {
            user_id: userId,
            name: normalizeNicknameForOnline(playerName),
            total_score: totalScore,
            total_stars: totalStars,
            best_level: bestLevel,
            levels_completed: levelsCompleted,
            scores_by_level: scoresByLevel,
            stars_by_level: starsByLevel,
            updated_at: new Date().toISOString()
        };

        const { error: upsertError } = await supabaseClient
            .from(ONLINE_RANKING_TABLE)
            .upsert(payload, { onConflict: "user_id" });

        if (upsertError) throw upsertError;
        return true;
    } catch (error) {
        console.warn("No se pudo guardar la puntuación online:", error);
        return false;
    }
}

async function loadOnlineRanking() {
    if (!isOnlineRankingReady()) {
        await initOnlineRanking();
    }

    if (!isOnlineRankingReady()) return null;

    try {
        const { data, error } = await supabaseClient
            .from(ONLINE_RANKING_TABLE)
            .select("user_id,name,total_score,total_stars,best_level,levels_completed")
            .order("total_score", { ascending: false })
            .order("best_level", { ascending: false })
            .order("total_stars", { ascending: false })
            .limit(100);

        if (error) throw error;
        return Array.isArray(data) ? data : [];
    } catch (error) {
        console.warn("No se pudo cargar el ranking online:", error);
        return null;
    }
}

function isOnlineRankingReady() {
    return Boolean(supabaseClient && onlineRankingUser && onlineRankingReady);
}
