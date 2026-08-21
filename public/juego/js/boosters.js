const BOOSTER_STORAGE_KEY = "candyBoosters";

const BOOSTER_PRICES = {

    hammer: 150,

    extraMoves: 200,

    bomb: 250,

    rocket: 300,

    swap: 350

};


let boosterInventory = (() => {

    try {

        const parsed =
            JSON.parse(
                localStorage.getItem(
                    BOOSTER_STORAGE_KEY
                ) || "{}"
            );

        return {

            hammer:
                Math.max(
                    0,
                    Math.floor(
                        Number(parsed.hammer) || 0
                    )
                ),

            extraMoves:
                Math.max(
                    0,
                    Math.floor(
                        Number(parsed.extraMoves) || 0
                    )
                ),

            bomb:
                Math.max(
                    0,
                    Math.floor(
                        Number(parsed.bomb) || 0
                    )
                ),

            rocket:
                Math.max(
                    0,
                    Math.floor(
                        Number(parsed.rocket) || 0
                    )
                ),

            swap:
                Math.max(
                    0,
                    Math.floor(
                        Number(parsed.swap) || 0
                    )
                )

        };

    } catch {

        return {

            hammer: 0,

            extraMoves: 0,

            bomb: 0,

            rocket: 0,

            swap: 0

        };

    }

})();


let activeBooster = null;


/*
|--------------------------------------------------------------------------
| GUARDAR POTENCIADORES
|--------------------------------------------------------------------------
*/

function saveBoosters() {

    localStorage.setItem(

        BOOSTER_STORAGE_KEY,

        JSON.stringify(
            boosterInventory
        )

    );

}


/*
|--------------------------------------------------------------------------
| OBTENER CANTIDAD DE POTENCIADORES
|--------------------------------------------------------------------------
*/

function getBoosterCount(type) {

    return boosterInventory[type] || 0;

}


/*
|--------------------------------------------------------------------------
| AGREGAR POTENCIADOR
|--------------------------------------------------------------------------
*/

function addBooster(
    type,
    amount = 1
) {

    if (!(type in boosterInventory)) {

        return;

    }

    boosterInventory[type] +=
        Math.max(
            0,
            Math.floor(
                Number(amount) || 0
            )
        );

    saveBoosters();

    renderBoosterInventory();

}


/*
|--------------------------------------------------------------------------
| UTILIZAR POTENCIADOR
|--------------------------------------------------------------------------
*/

function spendBooster(type) {

    if (
        getBoosterCount(type) <= 0
    ) {

        return false;

    }

    boosterInventory[type]--;

    saveBoosters();

    renderBoosterInventory();

    return true;

}


/*
|--------------------------------------------------------------------------
| MOSTRAR INVENTARIO
|--------------------------------------------------------------------------
*/

function renderBoosterInventory() {

    const hammerCount =
        document.getElementById(
            "hammerCount"
        );

    const movesCount =
        document.getElementById(
            "movesBoosterCount"
        );


    if (hammerCount) {

        hammerCount.textContent =
            getBoosterCount("hammer");

    }


    if (movesCount) {

        movesCount.textContent =
            getBoosterCount("extraMoves");

    }


    for (
        const type of [
            "bomb",
            "rocket",
            "swap"
        ]
    ) {

        const count =
            document.getElementById(
                `${type}BoosterCount`
            );

        if (count) {

            count.textContent =
                getBoosterCount(type);

        }

    }


    /*
    |--------------------------------------------------------------------------
    | MARTILLO
    |--------------------------------------------------------------------------
    */

    const hammerButton =
        document.getElementById(
            "hammerBoosterButton"
        );


    if (hammerButton) {

        hammerButton.disabled =
            state.levelComplete ||
            state.busy ||
            getBoosterCount("hammer") <= 0;

        hammerButton.classList.toggle(
            "active",
            activeBooster === "hammer"
        );

    }


    /*
    |--------------------------------------------------------------------------
    | +5 MOVIMIENTOS
    |--------------------------------------------------------------------------
    |
    | Este botón puede utilizarse incluso
    | cuando el jugador llegó a 0 movimientos.
    |--------------------------------------------------------------------------
    */

    const movesButton =
        document.getElementById(
            "movesBoosterButton"
        );


    if (movesButton) {

        movesButton.disabled =
            state.busy &&
            !state.levelComplete
                ? true
                : getBoosterCount("extraMoves") <= 0;

    }


    /*
    |--------------------------------------------------------------------------
    | BOMBA
    |--------------------------------------------------------------------------
    */

    const bombButton =
        document.getElementById(
            "bombBoosterButton"
        );


    if (bombButton) {

        bombButton.disabled =
            state.levelComplete ||
            state.busy ||
            getBoosterCount("bomb") <= 0;

    }


    /*
    |--------------------------------------------------------------------------
    | COHETE
    |--------------------------------------------------------------------------
    */

    const rocketButton =
        document.getElementById(
            "rocketBoosterButton"
        );


    if (rocketButton) {

        rocketButton.disabled =
            state.levelComplete ||
            state.busy ||
            getBoosterCount("rocket") <= 0;

    }


    /*
    |--------------------------------------------------------------------------
    | INTERCAMBIADOR
    |--------------------------------------------------------------------------
    */

    const swapButton =
        document.getElementById(
            "swapBoosterButton"
        );


    if (swapButton) {

        swapButton.disabled =
            state.levelComplete ||
            state.busy ||
            getBoosterCount("swap") <= 0;

    }


    /*
    |--------------------------------------------------------------------------
    | MENSAJE DEL MARTILLO
    |--------------------------------------------------------------------------
    */

    const hint =
        document.getElementById(
            "boosterHint"
        );


    if (hint) {

        hint.hidden =
            activeBooster !== "hammer";

        hint.textContent =
            activeBooster === "hammer"

                ? "🔨 Haz clic en una pieza o caja para usar el martillo."

                : "";

    }


    /*
    |--------------------------------------------------------------------------
    | MONEDAS DE LA TIENDA
    |--------------------------------------------------------------------------
    */

    const shopCoinsValue =
        document.getElementById(
            "shopCoinsValue"
        );


    if (
        shopCoinsValue &&
        typeof getCoins === "function"
    ) {

        shopCoinsValue.textContent =
            getCoins().toLocaleString(
                "es-UY"
            );

    }

}


/*
|--------------------------------------------------------------------------
| ABRIR TIENDA
|--------------------------------------------------------------------------
*/

function openShopModal() {

    const modal =
        document.getElementById(
            "shopModal"
        );

    if (!modal) {

        return;

    }

    renderBoosterInventory();

    modal.classList.remove(
        "hidden"
    );

    modal.setAttribute(
        "aria-hidden",
        "false"
    );

}


/*
|--------------------------------------------------------------------------
| CERRAR TIENDA
|--------------------------------------------------------------------------
*/

function closeShopModal() {

    const modal =
        document.getElementById(
            "shopModal"
        );

    if (!modal) {

        return;

    }

    modal.classList.add(
        "hidden"
    );

    modal.setAttribute(
        "aria-hidden",
        "true"
    );

}


/*
|--------------------------------------------------------------------------
| COMPRAR POTENCIADOR
|--------------------------------------------------------------------------
*/

function buyBooster(type) {

    const price =
        BOOSTER_PRICES[type];


    if (
        !price ||
        typeof spendCoins !== "function"
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | COMPROBAR MONEDAS
    |--------------------------------------------------------------------------
    */

    if (!spendCoins(price)) {

        const message =
            document.getElementById(
                "message"
            );


        if (message) {

            message.textContent =
                `🪙 Necesitas ${price} monedas.`;

        }

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | AGREGAR EL POTENCIADOR
    |--------------------------------------------------------------------------
    */

    addBooster(
        type,
        1
    );


    initAudio();

    playSelectSound();


    const labelMap = {

        hammer:
            "🔨 Martillo",

        extraMoves:
            "👟 +5 movimientos",

        bomb:
            "💣 Bomba 3×3",

        rocket:
            "🚀 Cohete",

        swap:
            "🔀 Intercambiador"

    };


    const label =
        labelMap[type] || type;


    const message =
        document.getElementById(
            "message"
        );


    if (message) {

        message.textContent =
            `✅ Compraste ${label}.`;

    }


    return true;

}


/*
|--------------------------------------------------------------------------
| CANCELAR POTENCIADOR ACTIVO
|--------------------------------------------------------------------------
*/

function cancelActiveBooster() {

    activeBooster = null;

    renderBoosterInventory();

}


/*
|--------------------------------------------------------------------------
| MARTILLO
|--------------------------------------------------------------------------
*/

function toggleHammerBooster() {

    if (
        getBoosterCount("hammer") <= 0 ||
        state.busy ||
        state.levelComplete
    ) {

        return;

    }


    activeBooster =
        activeBooster === "hammer"
            ? null
            : "hammer";


    renderBoosterInventory();

}


/*
|--------------------------------------------------------------------------
| +5 MOVIMIENTOS
|--------------------------------------------------------------------------
|
| IMPORTANTE:
|
| Esta función permite utilizar +5 movimientos
| después de haber perdido el nivel.
|
| Antes el problema era que state.busy quedaba
| en true al aparecer la pantalla de derrota.
|
|--------------------------------------------------------------------------
*/

function useExtraMovesBooster() {

    /*
    |--------------------------------------------------------------------------
    | Si estamos ocupados pero NO estamos en una
    | pantalla de nivel terminado, bloqueamos.
    |--------------------------------------------------------------------------
    */

    if (
        state.busy &&
        !state.levelComplete
    ) {

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | Comprobamos que tengamos el potenciador.
    |--------------------------------------------------------------------------
    */

    if (
        !spendBooster(
            "extraMoves"
        )
    ) {

        const message =
            document.getElementById(
                "message"
            );


        if (message) {

            message.textContent =
                "🪙 No tienes un potenciador de +5 movimientos.";

        }

        return false;

    }


    /*
    |--------------------------------------------------------------------------
    | AGREGAMOS 5 MOVIMIENTOS
    |--------------------------------------------------------------------------
    */

    state.moves += 5;


    /*
    |--------------------------------------------------------------------------
    | EL NIVEL VUELVE A ESTAR ACTIVO
    |--------------------------------------------------------------------------
    */

    state.levelComplete = false;

    state.busy = false;


    /*
    |--------------------------------------------------------------------------
    | LIMPIAMOS LA SELECCIÓN
    |--------------------------------------------------------------------------
    */

    state.selected = null;


    /*
    |--------------------------------------------------------------------------
    | OCULTAMOS LA PANTALLA DE NIVEL FALLIDO
    |--------------------------------------------------------------------------
    */

    const levelFailed =
        document.getElementById(
            "levelFailed"
        );


    if (levelFailed) {

        levelFailed.style.display =
            "none";

    }


    /*
    |--------------------------------------------------------------------------
    | MENSAJE
    |--------------------------------------------------------------------------
    */

    const message =
        document.getElementById(
            "message"
        );


    if (message) {

        message.textContent =
            "👟 ¡+5 movimientos! ¡Seguimos jugando!";

    }


    /*
    |--------------------------------------------------------------------------
    | SONIDOS
    |--------------------------------------------------------------------------
    */

    initAudio();

    playValidMoveSound();

    playObstacleSound(
        "swap"
    );


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR TABLERO
    |--------------------------------------------------------------------------
    */

    renderBoard();

    renderObjectives();

    renderBoosterInventory();


    return true;

}


/*
|--------------------------------------------------------------------------
| UTILIZAR MARTILLO
|--------------------------------------------------------------------------
*/

async function useHammerOnCell(
    row,
    col
) {

    if (
        activeBooster !== "hammer" ||
        state.busy ||
        state.levelComplete
    ) {

        return false;

    }


    if (
        getBoosterCount("hammer") <= 0
    ) {

        return false;

    }


    state.busy = true;

    activeBooster = null;

    spendBooster(
        "hammer"
    );


    if (
        !isPlayableCell(
            row,
            col
        )
    ) {

        state.busy = false;

        renderBoosterInventory();

        return false;

    }


    const boxHits =
        state.boxes[row]?.[col] || 0;

    const iceHits =
        state.ice[row]?.[col] || 0;


    let changed = false;


    if (boxHits > 0) {

        changed =
            damageBox(
                row,
                col
            );


        showComboMessage(
            0,
            1,
            "row"
        );


        const message =
            document.getElementById(
                "message"
            );


        if (message) {

            message.textContent =
                changed
                    ? "🔨 ¡Caja rota!"
                    : "🔨 ¡La caja recibió un golpe!";

        }

    }

    else if (iceHits > 0) {

        changed =
            damageIce(
                row,
                col
            );


        const message =
            document.getElementById(
                "message"
            );


        if (message) {

            message.textContent =
                changed
                    ? "🔨 ¡Hielo roto!"
                    : "🔨 ¡El hielo recibió un golpe!";

        }

    }

    else if (
        state.board[row][col] !== null &&
        state.board[row][col] !== undefined
    ) {

        changed =
            clearBoardCell(
                row,
                col
            );


        const message =
            document.getElementById(
                "message"
            );


        if (message) {

            message.textContent =
                "🔨 ¡Pieza destruida!";

        }

    }


    if (!changed) {

        state.busy = false;

        renderBoosterInventory();

        return false;

    }


    playSpecialActivateSound();

    renderBoard();

    renderObjectives();

    await sleep(160);

    collapseBoard();

    renderBoard();

    renderObjectives();

    await sleep(180);

    refillBoard();

    renderBoard();

    renderObjectives();

    await sleep(120);

    await finishWithCascades();


    state.busy = false;


    if (
        state.moves <= 0
    ) {

        checkLevelComplete();

    }


    renderBoard();

    renderObjectives();

    renderBoosterInventory();


    return true;

}


/*
|--------------------------------------------------------------------------
| SELECCIÓN DE TABLERO
|--------------------------------------------------------------------------
*/

function setBoardSelectionMode(
    type
) {

    activeBooster = type;

    renderBoosterInventory();

}


/*
|--------------------------------------------------------------------------
| CAMBIAR POTENCIADOR
|--------------------------------------------------------------------------
*/

function toggleBoardBooster(
    type
) {

    if (
        getBoosterCount(type) <= 0 ||
        state.busy ||
        state.levelComplete
    ) {

        return;

    }


    activeBooster =
        activeBooster === type
            ? null
            : type;


    renderBoosterInventory();

}


/*
|--------------------------------------------------------------------------
| BOMBA
|--------------------------------------------------------------------------
*/

async function useBombAtCell(
    row,
    col
) {

    if (
        activeBooster !== "bomb" ||
        state.busy ||
        state.levelComplete ||
        getBoosterCount("bomb") <= 0
    ) {

        return false;

    }


    if (
        !isPlayableCell(
            row,
            col
        )
    ) {

        return false;

    }


    state.busy = true;

    activeBooster = null;

    spendBooster("bomb");


    let changed = false;


    for (
        let r = row - 1;
        r <= row + 1;
        r++
    ) {

        for (
            let c = col - 1;
            c <= col + 1;
            c++
        ) {

            if (
                r < 0 ||
                r >= ROWS ||
                c < 0 ||
                c >= COLS ||
                !isPlayableCell(r, c)
            ) {

                continue;

            }


            if (
                state.boxes[r]?.[c] > 0
            ) {

                changed =
                    damageBox(
                        r,
                        c
                    ) || changed;

            }

            else if (
                state.ice[r]?.[c] > 0
            ) {

                changed =
                    damageIce(
                        r,
                        c
                    ) || changed;

            }

            else if (
                state.board[r]?.[c] !== null &&
                state.board[r]?.[c] !== undefined
            ) {

                changed =
                    clearBoardCell(
                        r,
                        c
                    ) || changed;

            }

        }

    }


    if (!changed) {

        state.busy = false;

        renderBoosterInventory();

        return false;

    }


    showComboMessage(
        9,
        1,
        "specialCombo"
    );


    if (window.ParticleFX) {

        ParticleFX.spawnBurst(
            row,
            col,
            "special",
            16
        );

    }


    playSpecialComboSound();

    playObstacleSound(
        "bomb"
    );


    renderBoard();

    renderObjectives();

    await sleep(180);

    collapseBoard();

    renderBoard();

    await sleep(160);

    refillBoard();

    renderBoard();

    await sleep(120);

    await finishWithCascades();


    state.busy = false;


    if (
        state.moves <= 0
    ) {

        checkLevelComplete();

    }


    renderBoard();

    renderObjectives();

    renderBoosterInventory();


    return true;

}


/*
|--------------------------------------------------------------------------
| COHETE
|--------------------------------------------------------------------------
*/

async function useRocketAtCell(
    row,
    col
) {

    if (
        activeBooster !== "rocket" ||
        state.busy ||
        state.levelComplete ||
        getBoosterCount("rocket") <= 0
    ) {

        return false;

    }


    if (
        !isPlayableCell(
            row,
            col
        )
    ) {

        return false;

    }


    state.busy = true;

    activeBooster = null;

    spendBooster("rocket");


    const clearRow =
        (
            (row + col) % 2 === 0
        );


    const positions = [];


    for (
        let i = 0;
        i < (
            clearRow
                ? COLS
                : ROWS
        );
        i++
    ) {

        const r =
            clearRow
                ? row
                : i;

        const c =
            clearRow
                ? i
                : col;


        if (
            isPlayableCell(
                r,
                c
            )
        ) {

            positions.push([
                r,
                c
            ]);

        }

    }


    for (
        const [
            r,
            c
        ] of positions
    ) {

        if (
            state.boxes[r]?.[c] > 0
        ) {

            damageBox(
                r,
                c
            );

        }

        else if (
            state.ice[r]?.[c] > 0
        ) {

            damageIce(
                r,
                c
            );

        }

        else {

            clearBoardCell(
                r,
                c
            );

        }

    }


    showComboMessage(
        positions.length,
        1,
        "specialCombo"
    );


    if (window.ParticleFX) {

        ParticleFX.spawnParticles(
            row,
            col,
            "special",
            18
        );

    }


    playSpecialActivateSound();

    playObstacleSound(
        "rocket"
    );


    renderBoard();

    renderObjectives();

    await sleep(180);

    collapseBoard();

    renderBoard();

    await sleep(160);

    refillBoard();

    renderBoard();

    await sleep(120);

    await finishWithCascades();


    state.busy = false;


    if (
        state.moves <= 0
    ) {

        checkLevelComplete();

    }


    renderBoard();

    renderObjectives();

    renderBoosterInventory();


    return true;

}


/*
|--------------------------------------------------------------------------
| INTERCAMBIADOR
|--------------------------------------------------------------------------
*/

async function activateSwapBooster(
    first,
    second
) {

    if (
        getBoosterCount("swap") <= 0 ||
        state.busy ||
        state.levelComplete
    ) {

        return false;

    }


    if (
        !canMoveCell(
            first.row,
            first.col
        ) ||
        !canMoveCell(
            second.row,
            second.col
        )
    ) {

        return false;

    }


    state.busy = true;

    spendBooster("swap");

    activeBooster = null;

    state.selected = null;

    state.moves =
        Math.max(
            0,
            state.moves - 1
        );


    swapPieces(
        first,
        second
    );


    playValidMoveSound();

    renderBoard();

    renderObjectives();

    await sleep(140);


    const matches =
        findMatches();


    if (matches.size > 0) {

        document.getElementById(
            "message"
        ).textContent =
            "🔀 ¡Intercambio!";


        await resolveBoard({

            preferredCells: [
                first,
                second
            ],

            showComboMessage

        });

    }

    else {

        document.getElementById(
            "message"
        ).textContent =
            "🔀 ¡Piezas intercambiadas!";

    }


    state.busy = false;

    renderBoard();

    renderObjectives();

    renderBoosterInventory();


    if (
        state.moves <= 0
    ) {

        checkLevelComplete();

    }


    return true;

}


/*
|--------------------------------------------------------------------------
| INICIALIZAR SISTEMA DE POTENCIADORES
|--------------------------------------------------------------------------
*/

function initializeBoostersSystem() {

    const shopButton =
        document.getElementById(
            "shopButton"
        );

    const closeShopButton =
        document.getElementById(
            "closeShopButton"
        );

    const closeShopButtonBottom =
        document.getElementById(
            "closeShopButtonBottom"
        );

    const buyHammerButton =
        document.getElementById(
            "buyHammerButton"
        );

    const buyMovesButton =
        document.getElementById(
            "buyMovesBoosterButton"
        );

    const buyBombButton =
        document.getElementById(
            "buyBombButton"
        );

    const buyRocketButton =
        document.getElementById(
            "buyRocketButton"
        );

    const buySwapButton =
        document.getElementById(
            "buySwapButton"
        );

    const hammerButton =
        document.getElementById(
            "hammerBoosterButton"
        );

    const movesButton =
        document.getElementById(
            "movesBoosterButton"
        );

    const bombButton =
        document.getElementById(
            "bombBoosterButton"
        );

    const rocketButton =
        document.getElementById(
            "rocketBoosterButton"
        );

    const swapButton =
        document.getElementById(
            "swapBoosterButton"
        );

    const shopModal =
        document.getElementById(
            "shopModal"
        );


    /*
    |--------------------------------------------------------------------------
    | ABRIR TIENDA
    |--------------------------------------------------------------------------
    */

    if (shopButton) {

        shopButton.addEventListener(
            "click",
            () => {

                initAudio();

                playSelectSound();

                openShopModal();

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR TIENDA
    |--------------------------------------------------------------------------
    */

    if (closeShopButton) {

        closeShopButton.addEventListener(
            "click",
            closeShopModal
        );

    }


    if (closeShopButtonBottom) {

        closeShopButtonBottom.addEventListener(
            "click",
            closeShopModal
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRAR MARTILLO
    |--------------------------------------------------------------------------
    */

    if (buyHammerButton) {

        buyHammerButton.addEventListener(
            "click",
            () => {

                if (
                    buyBooster("hammer")
                ) {

                    renderBoosterInventory();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRAR +5 MOVIMIENTOS
    |--------------------------------------------------------------------------
    */

    if (buyMovesButton) {

        buyMovesButton.addEventListener(
            "click",
            () => {

                if (
                    buyBooster(
                        "extraMoves"
                    )
                ) {

                    renderBoosterInventory();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRAR BOMBA
    |--------------------------------------------------------------------------
    */

    if (buyBombButton) {

        buyBombButton.addEventListener(
            "click",
            () => {

                if (
                    buyBooster("bomb")
                ) {

                    renderBoosterInventory();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRAR COHETE
    |--------------------------------------------------------------------------
    */

    if (buyRocketButton) {

        buyRocketButton.addEventListener(
            "click",
            () => {

                if (
                    buyBooster("rocket")
                ) {

                    renderBoosterInventory();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | COMPRAR INTERCAMBIADOR
    |--------------------------------------------------------------------------
    */

    if (buySwapButton) {

        buySwapButton.addEventListener(
            "click",
            () => {

                if (
                    buyBooster("swap")
                ) {

                    renderBoosterInventory();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | BOTONES DURANTE LA PARTIDA
    |--------------------------------------------------------------------------
    */

    if (hammerButton) {

        hammerButton.addEventListener(
            "click",
            toggleHammerBooster
        );

    }


    if (movesButton) {

        movesButton.addEventListener(
            "click",
            useExtraMovesBooster
        );

    }


    if (bombButton) {

        bombButton.addEventListener(
            "click",
            () =>
                toggleBoardBooster(
                    "bomb"
                )
        );

    }


    if (rocketButton) {

        rocketButton.addEventListener(
            "click",
            () =>
                toggleBoardBooster(
                    "rocket"
                )
        );

    }


    if (swapButton) {

        swapButton.addEventListener(
            "click",
            () =>
                toggleBoardBooster(
                    "swap"
                )
        );

    }


    /*
    |--------------------------------------------------------------------------
    | CERRAR TIENDA HACIENDO CLICK AFUERA
    |--------------------------------------------------------------------------
    */

    if (shopModal) {

        shopModal.addEventListener(
            "click",
            event => {

                if (
                    event.target ===
                    shopModal
                ) {

                    closeShopModal();

                }

            }
        );

    }


    /*
    |--------------------------------------------------------------------------
    | ACTUALIZAR INVENTARIO INICIAL
    |--------------------------------------------------------------------------
    */

    renderBoosterInventory();

}


initializeBoostersSystem();