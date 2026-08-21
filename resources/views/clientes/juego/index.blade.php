<!DOCTYPE html>
<html lang="es">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Candy Panadería Echeveste</title>


    <!-- ============================= -->
    <!-- ESTILOS DEL JUEGO              -->
    <!-- ============================= -->

    <link
        rel="stylesheet"
        href="{{ asset('juego/style.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('juego/visual-effects.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('juego/level-map.css') }}"
    >

    <link
        rel="stylesheet"
        href="{{ asset('juego/nickname.css') }}"
    >


    <style>

        .objectives {
            width: min(92vw, 640px);
            margin: 10px auto 0;
            padding: 10px 14px;
            background: rgba(255,255,255,.9);
            border-radius: 14px;
            box-shadow: 0 3px 8px rgba(0,0,0,.12);
            text-align: left;
        }


        .objectivesTitle {
            font-weight: 700;
            margin-bottom: 6px;
            text-align: center;
        }


        .objectiveItem {
            display: flex;
            gap: 8px;
            align-items: center;
            font-size: 14px;
            margin: 4px 0;
        }


        .objectiveItem.completed {
            opacity: .75;
            font-weight: 700;
        }


        .boxCell {
            background: #e8c08b !important;
            border: 3px solid #9b5e22;
            box-shadow:
                inset 0 0 0 2px #f7dfb9,
                0 3px 6px rgba(0,0,0,.2);
            font-size: clamp(28px,6vw,54px);
        }


        .boxCell.selected {
            outline: 4px solid #ff9800;
            filter: drop-shadow(0 0 8px #ffcc66);
        }


        /* ================================= */
        /* BOTÓN SALIR DEL JUEGO              */
        /* ================================= */

        .exitGameButton {
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 9999;

            display: inline-flex;
            align-items: center;
            justify-content: center;

            padding: 10px 16px;

            background: rgba(255,255,255,.95);
            color: #6b3e26;

            border: none;
            border-radius: 12px;

            text-decoration: none;
            font-weight: 700;

            box-shadow: 0 4px 12px rgba(0,0,0,.18);

            transition:
                transform .2s ease,
                box-shadow .2s ease;
        }


        .exitGameButton:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0,0,0,.25);

            color: #6b3e26;
            text-decoration: none;
        }


        @media (max-width: 600px) {

            .exitGameButton {
                top: 10px;
                right: 10px;
                padding: 8px 12px;
                font-size: 13px;
            }

        }

        /* =========================================================
   BOTÓN VOLVER A NIVELES
   ========================================================= */

.backToLevelsButton {
    position: fixed;
    top: 15px;
    left: 15px;
    z-index: 9999;

    padding: 10px 16px;

    background: rgba(255, 255, 255, 0.95);
    color: #6b3e26;

    border: 2px solid #d49a5b;
    border-radius: 12px;

    font-size: 14px;
    font-weight: 700;

    cursor: pointer;

    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.18);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease,
        background 0.2s ease;
}

.backToLevelsButton:hover {
    background: #fff;
    transform: translateY(-2px);

    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.25);
}

.backToLevelsButton:active {
    transform: translateY(0);
}

@media (max-width: 600px) {

    .backToLevelsButton {
        top: 10px;
        left: 10px;

        padding: 8px 12px;

        font-size: 12px;
    }

}
/* =========================================================
   RANKING DESTACADO
   ========================================================= */

.rankingMainButton {
    width: 100%;
    max-width: 420px;

    margin: 15px auto;

    padding: 14px 20px;

    border: none;
    border-radius: 16px;

    background: linear-gradient(
        135deg,
        #ffd54f,
        #ff9800
    );

    color: #5d351c;

    font-size: 18px;
    font-weight: 800;

    cursor: pointer;

    box-shadow:
        0 5px 12px rgba(0, 0, 0, 0.20);

    transition:
        transform 0.2s ease,
        box-shadow 0.2s ease;
}

.rankingMainButton:hover {
    transform: translateY(-3px);

    box-shadow:
        0 8px 18px rgba(0, 0, 0, 0.25);
}

.rankingMainButton:active {
    transform: translateY(0);
}

.rankingMainButton span {
    display: block;

    margin-top: 4px;

    font-size: 12px;
    font-weight: 600;

    opacity: 0.8;
}

@media (max-width: 600px) {

    .rankingMainButton {
        max-width: 100%;

        padding: 12px 15px;

        font-size: 16px;
    }

    .rankingMainButton span {
        font-size: 11px;
    }

}
/*
|--------------------------------------------------------------------------
| OPCIONES DEL NIVEL FALLIDO
|--------------------------------------------------------------------------
*/

.failedActions {

    display: flex;

    flex-direction: column;

    gap: 10px;

    width: min(90%, 360px);

    margin: 20px auto 0;

}


/*
|--------------------------------------------------------------------------
| BOTONES DEL NIVEL FALLIDO
|--------------------------------------------------------------------------
*/

.failedActions button {

    width: 100%;

}


/*
|--------------------------------------------------------------------------
| BOTÓN +5 MOVIMIENTOS
|--------------------------------------------------------------------------
*/

#failedExtraMovesButton {

    font-weight: 700;

}

    </style>

</head>


<body>


    <!-- ================================= -->
    <!-- SALIR DEL JUEGO                    -->
    <!-- ================================= -->

    <a
        href="{{ route('clientes.perfil') }}"
        class="exitGameButton"
    >
        🏠 Salir del juego
    </a>


    <!-- ================================= -->
    <!-- CONTROLES DE AUDIO                 -->
    <!-- ================================= -->

    <div
        id="audioControls"
        class="audioControls"
        aria-label="Controles de audio"
    >

        <button
            id="soundToggleButton"
            class="audioButton"
            type="button"
            aria-pressed="false"
        >
            🔊 Sonidos: ON
        </button>


        <button
            id="musicToggleButton"
            class="audioButton"
            type="button"
            aria-pressed="false"
        >
            🎵 Música: ON
        </button>

    </div>


    <!-- ================================= -->
    <!-- PANTALLA INICIAL                  -->
    <!-- ================================= -->

    <div
        id="startScreen"
        class="screen startScreen"
    >

        <div class="bakeryDecoration decoration1">
            🥐
        </div>

        <div class="bakeryDecoration decoration2">
            🍞
        </div>

        <div class="bakeryDecoration decoration3">
            🥖
        </div>

        <div class="bakeryDecoration decoration4">
            🍪
        </div>


        <div class="startCard">

            <div class="brandBadge">
                PANADERÍA ECHEVESTE
            </div>


            <div class="logo">
                🥐
            </div>


            <h1>
                Candy
                <span>Panadería Echeveste</span>
            </h1>


            <p class="subtitle">
                El desafío más dulce de la panadería
            </p>


            <div class="welcomeProducts">
                🥐 🍫 🥖 🍞 🥧 🥨
            </div>


            <button
                id="playButton"
                class="mainButton"
            >
                <span>▶</span>
                JUGAR
            </button>


            <p class="startHint">
                ¡Combina 3 o más productos iguales!
            </p>

        </div>

    </div>


    <!-- ================================= -->
    <!-- SELECCIÓN DE NIVELES              -->
    <!-- ================================= -->

    <div
        id="levelSelectScreen"
        class="screen hidden"
    >

        <div class="startCard levelSelectCard">

            <div class="brandBadge">
                PANADERÍA ECHEVESTE
            </div>


            <h1>
                🥐 Seleccionar nivel
            </h1>


            <p class="subtitle">
                ¡Supera los niveles y consigue ⭐⭐⭐!
            </p>


            <div class="mapPlayerStats">

                <div
                    id="livesDisplay"
                    class="livesDisplay"
                ></div>


                <div
                    id="coinsDisplay"
                    class="coinsDisplay"
                ></div>


                <button
                    id="buyLifeButton"
                    class="buyLifeButton"
                    type="button"
                    hidden
                ></button>

            </div>


            <!-- =========================================================
     BOTONES DE PRUEBA / DESARROLLO
     
     Estos botones se utilizan únicamente durante el desarrollo
     del juego para realizar pruebas rápidas:

     - Recargar vidas
     - Agregar 500 monedas
     - Desbloquear los 60 niveles

     IMPORTANTE:
     Estos botones no deberían estar visibles en la versión final
     del juego para los jugadores.
     ========================================================= -->

<!--
<button
    id="restoreLivesButton"
    class="restoreLivesButton"
    type="button"
>
    🛠️ Recargar vidas (pruebas)
</button>


<button
    id="devCoinsButton"
    class="restoreLivesButton"
    type="button"
>
    🛠️ +500 monedas (pruebas)
</button>


<button
    id="devUnlockButton"
    class="restoreLivesButton"
    type="button"
>
    🛠️ Desbloquear 60 niveles (pruebas)
</button>
-->

            <button
                 id="rankingButton"
                class="rankingMainButton"
                type="button"
    >
                 🏆 VER RANKING
           </button>


            <button
                id="shopButton"
                class="secondaryButton shopButton"
                type="button"
            >
                🛒 Potenciadores
            </button>


            <div
                id="levelButtons"
                class="levelButtons levelMap"
            ></div>


            <div class="levelMenuActions">

            


                <button
                    id="backToStartButton"
                    class="secondaryButton"
                >
                    ← Volver
                </button>

            </div>

        </div>

    </div>


    <!-- ================================= -->
    <!-- PANTALLA DEL JUEGO                -->
    <!-- ================================= -->

    <div id="gameScreen" class="screen hidden">

    <!-- Volver al mapa de niveles -->
    <button
        id="backToLevelsButton"
        class="backToLevelsButton"
        type="button"
    >
        ← Volver a niveles
    </button>

    <div class="game">

            <div class="gameHeader">

                <div class="miniLogo">
                    🥐
                </div>


                <div>

                    <h1>
                        Candy Panadería Echeveste
                    </h1>


                    <h2 id="levelTitle">
                        Nivel 1
                    </h2>

                </div>

            </div>


            <div class="info">

                <div>

                    <span>
                        🏆 Puntos
                    </span>

                    <strong id="score">
                        0
                    </strong>

                </div>


                <div>

                    <span>
                        👣 Movimientos
                    </span>

                    <strong id="moves">
                        30
                    </strong>

                </div>


                <div>

                    <span>
                        🎯 Objetivo
                    </span>

                    <strong id="target">
                        500
                    </strong>

                </div>

            </div>


            <!-- ================================= -->
            <!-- POTENCIADORES                      -->
            <!-- ================================= -->

            <div
                class="boosterBar"
                id="boosterBar"
            >

                <button
                    id="hammerBoosterButton"
                    class="boosterButton"
                    type="button"
                    title="Rompe una pieza o daña una caja"
                >

                    🔨

                    <span>
                        Martillo
                    </span>

                    <b id="hammerCount">
                        0
                    </b>

                </button>


                <button
                    id="movesBoosterButton"
                    class="boosterButton"
                    type="button"
                    title="Agrega 5 movimientos"
                >

                    👟

                    <span>
                        +5 movimientos
                    </span>

                    <b id="movesBoosterCount">
                        0
                    </b>

                </button>


                <button
                    id="bombBoosterButton"
                    class="boosterButton"
                    type="button"
                    title="Elimina un área 3×3"
                >

                    💣

                    <span>
                        Bomba
                    </span>

                    <b id="bombBoosterCount">
                        0
                    </b>

                </button>


                <button
                    id="rocketBoosterButton"
                    class="boosterButton"
                    type="button"
                    title="Elimina una fila o columna"
                >

                    🚀

                    <span>
                        Cohete
                    </span>

                    <b id="rocketBoosterCount">
                        0
                    </b>

                </button>


                <button
                    id="swapBoosterButton"
                    class="boosterButton"
                    type="button"
                    title="Intercambia dos piezas cualesquiera"
                >

                    🔀

                    <span>
                        Intercambiar
                    </span>

                    <b id="swapBoosterCount">
                        0
                    </b>

                </button>

            </div>


            <div
                id="boosterHint"
                class="boosterHint"
                hidden
            ></div>


            <div id="boardStage">

                <div id="board"></div>

                <div id="comboMessage"></div>

            </div>


            <div
                id="objectives"
                class="objectives"
                style="display:none;"
            ></div>


            <p id="message">
                ¡Combina 3 productos iguales!
            </p>


            <!-- ================================= -->
            <!-- NIVEL COMPLETADO                  -->
            <!-- ================================= -->

            <div id="levelComplete">

                <div class="resultIcon">
                    🎉
                </div>


                <h2>
                    ¡Nivel completado!
                </h2>


                <div
                    id="completionStars"
                    class="completionStars"
                >
                    ☆☆☆
                </div>


                <p id="completionScore">
                    Puntuación: 0
                </p>


                <p id="completionBest">
                    Récord: 0
                </p>


                <button id="nextLevelButton">
                    Siguiente nivel →
                </button>

            </div>


            <!-- ================================= -->
            <!-- NIVEL FALLIDO                     -->
            <!-- ================================= -->

            <!-- ========================================================= -->
<!-- NIVEL FALLIDO                                            -->
<!-- ========================================================= -->

<div id="levelFailed">

    <div class="resultIcon">
        😔
    </div>

    <h2>
        ¡Te quedaste sin movimientos!
    </h2>

    <p id="failedScore"></p>

    <p id="failedTarget"></p>


    <!-- ===================================================== -->
    <!-- OPCIONES AL QUEDARSE SIN MOVIMIENTOS                  -->
    <!-- ===================================================== -->

    <div class="failedActions">

        <!-- ================================================= -->
        <!-- COMPRAR POTENCIADORES                              -->
        <!-- ================================================= -->

        <button
            id="failedShopButton"
            class="mainButton"
            type="button"
        >
            🛒 Comprar potenciadores
        </button>


        <!-- ================================================= -->
        <!-- COMPRAR +5 MOVIMIENTOS                             -->
        <!-- ================================================= -->

        <button
            id="failedExtraMovesButton"
            class="secondaryButton"
            type="button"
        >
            👟 Comprar +5 movimientos
        </button>


        <!-- ================================================= -->
        <!-- REINTENTAR NIVEL                                   -->
        <!-- ================================================= -->

        <button
            id="retryLevelButton"
            class="secondaryButton"
            type="button"
        >
            🔄 Reintentar nivel
        </button>


        <!-- ================================================= -->
        <!-- VOLVER AL MAPA                                     -->
        <!-- ================================================= -->

        <button
            id="failedBackToLevelsButton"
            class="secondaryButton"
            type="button"
        >
            🗺️ Volver a niveles
        </button>

    </div>

</div>

        </div>

    </div>


    <!-- ================================= -->
    <!-- RANKING                           -->
    <!-- ================================= -->

    <div
        id="rankingModal"
        class="rankingOverlay hidden"
        aria-hidden="true"
    >

        <div
            class="rankingCard"
            role="dialog"
            aria-modal="true"
            aria-labelledby="rankingTitle"
        >

            <div class="rankingHeader">

                <div>

                    <div class="brandBadge">
                        PANADERÍA ECHEVESTE
                    </div>


                    <h2 id="rankingTitle">
                        🏆 Ranking online
                    </h2>

                </div>


                <button
                    id="closeRankingButton"
                    class="rankingCloseButton"
                    aria-label="Cerrar ranking"
                >
                    ✕
                </button>

            </div>


            <p class="rankingSubtitle">
                Mejores puntuaciones de todos los jugadores.
            </p>


            <div class="rankingColumns">

                <span>
                    Pos.
                </span>

                <span>
                    Jugador
                </span>

                <span>
                    Nivel
                </span>

                <span>
                    ⭐
                </span>

                <span>
                    Puntos
                </span>

            </div>


            <div
                id="rankingList"
                class="rankingList"
            ></div>


            <div
                id="rankingPlayerInfo"
                class="rankingPlayerInfo"
            ></div>


            <button
                id="closeRankingButtonBottom"
                class="secondaryButton"
            >
                Cerrar
            </button>

        </div>

    </div>


    <!-- ================================= -->
    <!-- TIENDA DE POTENCIADORES            -->
    <!-- ================================= -->

    <div
        id="shopModal"
        class="shopOverlay hidden"
        aria-hidden="true"
    >

        <div
            class="shopCard"
            role="dialog"
            aria-modal="true"
            aria-labelledby="shopTitle"
        >

            <div class="shopHeader">

                <div>

                    <div class="brandBadge">
                        PANADERÍA ECHEVESTE
                    </div>


                    <h2 id="shopTitle">
                        🛒 Potenciadores
                    </h2>

                </div>


                <button
                    id="closeShopButton"
                    class="rankingCloseButton"
                    aria-label="Cerrar tienda"
                >
                    ✕
                </button>

            </div>


            <p class="shopSubtitle">
                Usa tus monedas para comprar ayuda durante los niveles.
            </p>


            <div class="shopCoins">

                <span
                    class="coinsIcon"
                    aria-hidden="true"
                >
                    $
                </span>


                <strong id="shopCoinsValue">
                    0
                </strong>


                <span>
                    monedas
                </span>

            </div>


            <div class="shopItems">


                <div class="shopItem">

                    <div class="shopItemIcon">
                        🔨
                    </div>


                    <div class="shopItemText">

                        <strong>
                            Martillo
                        </strong>

                        <span>
                            Rompe una pieza o daña una caja sin gastar un movimiento.
                        </span>

                    </div>


                    <div class="shopItemAction">

                        <span class="shopPrice">
                            150
                            <span
                                class="coinBadge"
                                aria-hidden="true"
                            >
                                $
                            </span>
                        </span>


                        <button
                            id="buyHammerButton"
                            class="mainButton smallShopButton"
                            type="button"
                        >
                            Comprar
                        </button>

                    </div>

                </div>


                <div class="shopItem">

                    <div class="shopItemIcon">
                        💣
                    </div>


                    <div class="shopItemText">

                        <strong>
                            Bomba 3×3
                        </strong>

                        <span>
                            Elimina y golpea todo dentro de un área de 3×3 sin gastar un movimiento.
                        </span>

                    </div>


                    <div class="shopItemAction">

                        <span class="shopPrice">
                            250
                            <span
                                class="coinBadge"
                                aria-hidden="true"
                            >
                                $
                            </span>
                        </span>


                        <button
                            id="buyBombButton"
                            class="mainButton smallShopButton"
                            type="button"
                        >
                            Comprar
                        </button>

                    </div>

                </div>


                <div class="shopItem">

                    <div class="shopItemIcon">
                        🚀
                    </div>


                    <div class="shopItemText">

                        <strong>
                            Cohete
                        </strong>

                        <span>
                            Elimina una fila o columna completa sin gastar un movimiento.
                        </span>

                    </div>


                    <div class="shopItemAction">

                        <span class="shopPrice">
                            300
                            <span
                                class="coinBadge"
                                aria-hidden="true"
                            >
                                $
                            </span>
                        </span>


                        <button
                            id="buyRocketButton"
                            class="mainButton smallShopButton"
                            type="button"
                        >
                            Comprar
                        </button>

                    </div>

                </div>


                <div class="shopItem">

                    <div class="shopItemIcon">
                        🔀
                    </div>


                    <div class="shopItemText">

                        <strong>
                            Intercambiador
                        </strong>

                        <span>
                            Intercambia dos piezas cualesquiera sin gastar un movimiento.
                        </span>

                    </div>


                    <div class="shopItemAction">

                        <span class="shopPrice">
                            350
                            <span
                                class="coinBadge"
                                aria-hidden="true"
                            >
                                $
                            </span>
                        </span>


                        <button
                            id="buySwapButton"
                            class="mainButton smallShopButton"
                            type="button"
                        >
                            Comprar
                        </button>

                    </div>

                </div>


                <div class="shopItem">

                    <div class="shopItemIcon">
                        👟
                    </div>


                    <div class="shopItemText">

                        <strong>
                            +5 movimientos
                        </strong>

                        <span>
                            Agrega cinco movimientos al nivel actual.
                        </span>

                    </div>


                    <div class="shopItemAction">

                        <span class="shopPrice">
                            200
                            <span
                                class="coinBadge"
                                aria-hidden="true"
                            >
                                $
                            </span>
                        </span>


                        <button
                            id="buyMovesBoosterButton"
                            class="mainButton smallShopButton"
                            type="button"
                        >
                            Comprar
                        </button>

                    </div>

                </div>

            </div>


            <button
                id="closeShopButtonBottom"
                class="secondaryButton"
            >
                Cerrar
            </button>

        </div>

    </div>


    <!-- ================================= -->
    <!-- NOMBRE / APODO DEL JUGADOR        -->
    <!-- ================================= -->

    <div
        id="nicknameModal"
        class="nicknameOverlay hidden"
        aria-hidden="true"
    >

        <div
            class="nicknameCard"
            role="dialog"
            aria-modal="true"
            aria-labelledby="nicknameTitle"
        >

            <div class="nicknameIcon">
                🥐
            </div>


            <h2 id="nicknameTitle">
                ¿Cómo quieres aparecer?
            </h2>


            <p>
                Elige un nombre o apodo para usarlo en el ranking.
            </p>


            <input
                id="nicknameInput"
                type="text"
                maxlength="15"
                minlength="3"
                autocomplete="nickname"
                placeholder="Tu apodo"
                aria-label="Nombre o apodo"
            >


            <div
                id="nicknameError"
                class="nicknameError"
            ></div>


            <button
                id="saveNicknameButton"
                class="mainButton nicknameButton"
            >
                CONTINUAR →
            </button>

        </div>

    </div>


    <!-- ================================= -->
    <!-- SUPABASE                           -->
    <!-- ================================= -->

    <script src="https://cdn.jsdelivr.net/npm/@supabase/supabase-js@2"></script>


    <!-- ================================= -->
    <!-- JAVASCRIPT DEL JUEGO               -->
    <!-- ================================= -->

    <script src="{{ asset('juego/js/supabase-config.js') }}"></script>

    <script src="{{ asset('juego/js/online-ranking.js') }}"></script>

    <script src="{{ asset('juego/js/config.js') }}"></script>

    <script src="{{ asset('juego/js/state.js') }}"></script>

    <script src="{{ asset('juego/js/lives.js') }}"></script>

    <script src="{{ asset('juego/js/coins.js') }}"></script>

    <script src="{{ asset('juego/js/audio.js') }}"></script>

    <script src="{{ asset('juego/js/board.js') }}"></script>

    <script src="{{ asset('juego/js/specials.js') }}"></script>

    <script src="{{ asset('juego/js/levels.js') }}"></script>

    <script src="{{ asset('juego/js/ranking.js') }}"></script>

    <script src="{{ asset('juego/js/particles.js') }}"></script>

    <script src="{{ asset('juego/js/boosters.js') }}"></script>

    <script src="{{ asset('juego/js/main.js') }}"></script>


</body>

</html>