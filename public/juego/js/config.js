const ROWS = 8;
const COLS = 8;

const PIECES = [
    "🥐",
    "🍫",
    "🥖",
    "🍞",
    "🥧",
    "🥨"
];

const PIECE_NAMES = [
    "medialunas",
    "chocolates",
    "pan francés",
    "pan integral",
    "tortas de fiambre",
    "pretzels"
];

/*
 * Campaña de 60 niveles.
 *
 * 1-10  : aprendizaje + objetivos + especiales
 * 11-20 : cajas de 1 y 2 golpes
 * 21-30 : introducción del hielo
 * 31-40 : hielo de 2 capas + cajas
 * 41-50 : hielo + cajas + objetivos
 * 51-60 : formas de tablero + hielo + especiales
 */
const levels = [
    // 1-5: aprendizaje y combinaciones
    { target: 500, moves: 30, objectives: [] },
    { target: 700, moves: 30, objectives: [] },
    { target: 900, moves: 29, objectives: [] },
    { target: 1200, moves: 28, objectives: [] },
    { target: 1500, moves: 28, objectives: [] },

    // 6-10: objetivos especiales
    { target: 1800, moves: 28, objectives: [{ type: "clear", piece: 1, count: 10 }] },
    { target: 2200, moves: 27, objectives: [{ type: "special", count: 2 }] },
    { target: 2600, moves: 27, objectives: [{ type: "rainbow", count: 1 }] },
    { target: 3200, moves: 27, objectives: [
        { type: "clear", piece: 0, count: 12 }, { type: "special", count: 2 }
    ] },
    { target: 4000, moves: 26, objectives: [
        { type: "rainbow", count: 1 }, { type: "clear", piece: 1, count: 12 }
    ] },

    // 11-12: cajas de 1 golpe
    { target: 4500, moves: 30, objectives: [{ type: "box", count: 8, hits: 1 }] },
    { target: 5000, moves: 30, objectives: [{ type: "box", count: 10, hits: 1 }] },

    // 13-20: cajas de 2 golpes + objetivos
    { target: 5500, moves: 29, objectives: [
        { type: "clear", piece: 1, count: 10 }, { type: "box", count: 10, hits: 2 }
    ] },
    { target: 6000, moves: 29, objectives: [
        { type: "clear", piece: 0, count: 12 }, { type: "special", count: 2 }, { type: "box", count: 10, hits: 2 }
    ] },
    { target: 6500, moves: 28, objectives: [
        { type: "special", count: 2 }, { type: "box", count: 10, hits: 2 }
    ] },
    { target: 7000, moves: 28, objectives: [
        { type: "rainbow", count: 1 }, { type: "box", count: 12, hits: 2 }
    ] },
    { target: 7500, moves: 28, objectives: [
        { type: "clear", piece: 1, count: 15 }, { type: "box", count: 12, hits: 2 }
    ] },
    { target: 8500, moves: 27, objectives: [
        { type: "special", count: 3 }, { type: "rainbow", count: 1 }, { type: "box", count: 12, hits: 2 }
    ] },
    { target: 9500, moves: 27, objectives: [
        { type: "clear", piece: 0, count: 15 }, { type: "box", count: 15, hits: 2 }
    ] },
    { target: 11000, moves: 30, objectives: [
        { type: "rainbow", count: 2 }, { type: "special", count: 3 },
        { type: "clear", piece: 1, count: 15 }, { type: "box", count: 15, hits: 2 }
    ] },

    // 21-30: introducción del hielo, 1 capa
    { target: 5000, moves: 32, iceCount: 4, iceHits: 1, objectives: [{ type: "ice", count: 4, hits: 1 }] },
    { target: 5200, moves: 32, iceCount: 5, iceHits: 1, objectives: [{ type: "ice", count: 5, hits: 1 }] },
    { target: 5500, moves: 31, iceCount: 6, iceHits: 1, objectives: [{ type: "ice", count: 6, hits: 1 }] },
    { target: 5800, moves: 31, iceCount: 7, iceHits: 1, objectives: [
        { type: "ice", count: 7, hits: 1 }, { type: "special", count: 1 }
    ] },
    { target: 6200, moves: 31, iceCount: 8, iceHits: 1, objectives: [
        { type: "ice", count: 8, hits: 1 }, { type: "rainbow", count: 1 }
    ] },
    { target: 6500, moves: 30, iceCount: 9, iceHits: 1, objectives: [
        { type: "ice", count: 9, hits: 1 }, { type: "clear", piece: 1, count: 10 }
    ] },
    { target: 6800, moves: 30, iceCount: 10, iceHits: 1, objectives: [
        { type: "ice", count: 10, hits: 1 }, { type: "special", count: 2 }
    ] },
    { target: 7200, moves: 30, iceCount: 11, iceHits: 1, objectives: [
        { type: "ice", count: 11, hits: 1 }, { type: "clear", piece: 0, count: 12 }
    ] },
    { target: 7600, moves: 29, iceCount: 12, iceHits: 1, objectives: [
        { type: "ice", count: 12, hits: 1 }, { type: "rainbow", count: 1 }, { type: "special", count: 1 }
    ] },
    { target: 8000, moves: 30, iceCount: 14, iceHits: 1, objectives: [
        { type: "ice", count: 14, hits: 1 }, { type: "clear", piece: 1, count: 12 }
    ] },

    // 31-40: hielo de 2 capas + cajas
    { target: 7500, moves: 32, iceCount: 6, iceHits: 2, objectives: [
        { type: "ice", count: 6, hits: 2 }, { type: "box", count: 6, hits: 1 }
    ] },
    { target: 7800, moves: 32, iceCount: 7, iceHits: 2, objectives: [
        { type: "ice", count: 7, hits: 2 }, { type: "box", count: 7, hits: 1 }
    ] },
    { target: 8200, moves: 31, iceCount: 8, iceHits: 2, objectives: [
        { type: "ice", count: 8, hits: 2 }, { type: "box", count: 8, hits: 1 }
    ] },
    { target: 8600, moves: 31, iceCount: 9, iceHits: 2, objectives: [
        { type: "ice", count: 9, hits: 2 }, { type: "box", count: 8, hits: 2 }
    ] },
    { target: 9000, moves: 31, iceCount: 10, iceHits: 2, objectives: [
        { type: "ice", count: 10, hits: 2 }, { type: "box", count: 10, hits: 2 }, { type: "special", count: 1 }
    ] },
    { target: 9400, moves: 30, iceCount: 11, iceHits: 2, objectives: [
        { type: "ice", count: 11, hits: 2 }, { type: "box", count: 10, hits: 2 }, { type: "rainbow", count: 1 }
    ] },
    { target: 9800, moves: 30, iceCount: 12, iceHits: 2, objectives: [
        { type: "ice", count: 12, hits: 2 }, { type: "box", count: 12, hits: 2 }
    ] },
    { target: 10200, moves: 30, iceCount: 13, iceHits: 2, objectives: [
        { type: "ice", count: 13, hits: 2 }, { type: "box", count: 12, hits: 2 }, { type: "special", count: 2 }
    ] },
    { target: 10800, moves: 29, iceCount: 14, iceHits: 2, objectives: [
        { type: "ice", count: 14, hits: 2 }, { type: "box", count: 14, hits: 2 }, { type: "rainbow", count: 1 }
    ] },
    { target: 11500, moves: 30, iceCount: 15, iceHits: 2, objectives: [
        { type: "ice", count: 15, hits: 2 }, { type: "box", count: 15, hits: 2 }, { type: "special", count: 2 }
    ] },

    // 41-50: hielo + cajas + objetivos combinados
    { target: 10000, moves: 32, iceCount: 8, iceHits: 2, objectives: [
        { type: "ice", count: 8, hits: 2 }, { type: "box", count: 8, hits: 2 }, { type: "clear", piece: 1, count: 10 }
    ] },
    { target: 10400, moves: 32, iceCount: 9, iceHits: 2, objectives: [
        { type: "ice", count: 9, hits: 2 }, { type: "box", count: 9, hits: 2 }, { type: "clear", piece: 0, count: 12 }
    ] },
    { target: 10800, moves: 31, iceCount: 10, iceHits: 2, objectives: [
        { type: "ice", count: 10, hits: 2 }, { type: "box", count: 10, hits: 2 }, { type: "special", count: 2 }
    ] },
    { target: 11200, moves: 31, iceCount: 11, iceHits: 2, objectives: [
        { type: "ice", count: 11, hits: 2 }, { type: "box", count: 11, hits: 2 }, { type: "rainbow", count: 1 }
    ] },
    { target: 11600, moves: 31, iceCount: 12, iceHits: 2, objectives: [
        { type: "ice", count: 12, hits: 2 }, { type: "box", count: 12, hits: 2 }, { type: "clear", piece: 1, count: 15 }
    ] },
    { target: 12000, moves: 30, iceCount: 13, iceHits: 2, objectives: [
        { type: "ice", count: 13, hits: 2 }, { type: "box", count: 13, hits: 2 }, { type: "special", count: 3 }
    ] },
    { target: 12500, moves: 30, iceCount: 14, iceHits: 2, objectives: [
        { type: "ice", count: 14, hits: 2 }, { type: "box", count: 14, hits: 2 }, { type: "rainbow", count: 1 }, { type: "clear", piece: 0, count: 15 }
    ] },
    { target: 13000, moves: 30, iceCount: 15, iceHits: 2, objectives: [
        { type: "ice", count: 15, hits: 2 }, { type: "box", count: 15, hits: 2 }, { type: "special", count: 3 }
    ] },
    { target: 13600, moves: 29, iceCount: 16, iceHits: 2, objectives: [
        { type: "ice", count: 16, hits: 2 }, { type: "box", count: 16, hits: 2 }, { type: "rainbow", count: 2 }
    ] },
    { target: 14200, moves: 30, iceCount: 17, iceHits: 2, objectives: [
        { type: "ice", count: 17, hits: 2 }, { type: "box", count: 17, hits: 2 }, { type: "special", count: 3 }, { type: "clear", piece: 1, count: 18 }
    ] },

    // 51-60: nuevas formas de tablero + hielo + especiales
    { target: 11000, moves: 32, shape: "heart", iceCount: 6, iceHits: 1, objectives: [
        { type: "ice", count: 6, hits: 1 }, { type: "special", count: 2 }
    ] },
    { target: 11500, moves: 32, shape: "diamond", iceCount: 7, iceHits: 1, objectives: [
        { type: "ice", count: 7, hits: 1 }, { type: "rainbow", count: 1 }
    ] },
    { target: 12000, moves: 31, shape: "cross", iceCount: 8, iceHits: 1, objectives: [
        { type: "ice", count: 8, hits: 1 }, { type: "special", count: 2 }, { type: "clear", piece: 1, count: 12 }
    ] },
    { target: 12500, moves: 31, shape: "hourglass", iceCount: 9, iceHits: 1, objectives: [
        { type: "ice", count: 9, hits: 1 }, { type: "rainbow", count: 1 }, { type: "special", count: 2 }
    ] },
    { target: 13000, moves: 31, shape: "donut", iceCount: 10, iceHits: 1, objectives: [
        { type: "ice", count: 10, hits: 1 }, { type: "box", count: 8, hits: 1 }, { type: "special", count: 2 }
    ] },
    { target: 13600, moves: 30, shape: "split", iceCount: 11, iceHits: 2, objectives: [
        { type: "ice", count: 11, hits: 2 }, { type: "special", count: 3 }, { type: "rainbow", count: 1 }
    ] },
    { target: 14200, moves: 30, shape: "pyramid", iceCount: 12, iceHits: 2, objectives: [
        { type: "ice", count: 12, hits: 2 }, { type: "box", count: 10, hits: 2 }, { type: "special", count: 3 }
    ] },
    { target: 14800, moves: 29, shape: "butterfly", iceCount: 13, iceHits: 2, objectives: [
        { type: "ice", count: 13, hits: 2 }, { type: "box", count: 12, hits: 2 }, { type: "rainbow", count: 2 }
    ] },
    { target: 15500, moves: 29, shape: "zigzag", iceCount: 14, iceHits: 2, objectives: [
        { type: "ice", count: 14, hits: 2 }, { type: "box", count: 13, hits: 2 }, { type: "special", count: 3 }, { type: "clear", piece: 0, count: 16 }
    ] },
    { target: 16500, moves: 30, shape: "fortress", iceCount: 15, iceHits: 2, objectives: [
        { type: "ice", count: 15, hits: 2 }, { type: "box", count: 15, hits: 2 }, { type: "special", count: 4 }, { type: "rainbow", count: 2 }
    ] }
];

const TOTAL_LEVELS = levels.length;
