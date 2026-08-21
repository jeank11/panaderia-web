let audioContext = null;
let soundEnabled = localStorage.getItem("candySoundEnabled") !== "0";
let musicEnabled = localStorage.getItem("candyMusicEnabled") !== "0";
let musicTimer = null;
let musicStep = 0;

const MUSIC_NOTES = [261.63, 329.63, 392.00, 523.25, 392.00, 329.63, 293.66, 392.00];

function initAudio() {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    if (!AudioContextClass) return;
    if (!audioContext) audioContext = new AudioContextClass();
    if (audioContext.state === "suspended") audioContext.resume();
    if (musicEnabled) startMusic();
    updateAudioControls();
}

function playTone(frequency, duration = 0.12, type = "sine", volume = 0.045, startDelay = 0) {
    if (!soundEnabled) return;
    initAudioContextOnly();
    if (!audioContext) return;

    const oscillator = audioContext.createOscillator();
    const gain = audioContext.createGain();
    const startTime = audioContext.currentTime + startDelay;

    oscillator.type = type;
    oscillator.frequency.setValueAtTime(frequency, startTime);
    gain.gain.setValueAtTime(0.0001, startTime);
    gain.gain.exponentialRampToValueAtTime(volume, startTime + 0.015);
    gain.gain.exponentialRampToValueAtTime(0.0001, startTime + duration);

    oscillator.connect(gain);
    gain.connect(audioContext.destination);
    oscillator.start(startTime);
    oscillator.stop(startTime + duration + 0.02);
}

function initAudioContextOnly() {
    const AudioContextClass = window.AudioContext || window.webkitAudioContext;
    if (!AudioContextClass) return;
    if (!audioContext) audioContext = new AudioContextClass();
    if (audioContext.state === "suspended") audioContext.resume();
}

function playMusicNote(frequency) {
    if (!musicEnabled) return;
    initAudioContextOnly();
    if (!audioContext) return;

    const now = audioContext.currentTime;
    const osc = audioContext.createOscillator();
    const gain = audioContext.createGain();
    osc.type = "sine";
    osc.frequency.setValueAtTime(frequency, now);
    gain.gain.setValueAtTime(0.0001, now);
    gain.gain.exponentialRampToValueAtTime(0.014, now + 0.04);
    gain.gain.exponentialRampToValueAtTime(0.0001, now + 0.42);
    osc.connect(gain);
    gain.connect(audioContext.destination);
    osc.start(now);
    osc.stop(now + 0.46);
}

function musicTick() {
    if (!musicEnabled) return;
    playMusicNote(MUSIC_NOTES[musicStep % MUSIC_NOTES.length]);
    musicStep += 1;
}

function startMusic() {
    if (!musicEnabled) return;
    initAudioContextOnly();
    if (!audioContext) return;
    if (musicTimer !== null) return;
    musicTick();
    musicTimer = window.setInterval(musicTick, 480);
}

function stopMusic() {
    if (musicTimer !== null) {
        window.clearInterval(musicTimer);
        musicTimer = null;
    }
}

function setSoundEnabled(enabled) {
    soundEnabled = Boolean(enabled);
    localStorage.setItem("candySoundEnabled", soundEnabled ? "1" : "0");
    updateAudioControls();
}

function setMusicEnabled(enabled) {
    musicEnabled = Boolean(enabled);
    localStorage.setItem("candyMusicEnabled", musicEnabled ? "1" : "0");
    if (musicEnabled) {
        initAudio();
        startMusic();
    } else {
        stopMusic();
    }
    updateAudioControls();
}

function updateAudioControls() {
    const soundButton = document.getElementById("soundToggleButton");
    const musicButton = document.getElementById("musicToggleButton");
    if (soundButton) {
        soundButton.textContent = soundEnabled ? "🔊 Sonidos: ON" : "🔇 Sonidos: OFF";
        soundButton.classList.toggle("muted", !soundEnabled);
        soundButton.setAttribute("aria-pressed", String(soundEnabled));
    }
    if (musicButton) {
        musicButton.textContent = musicEnabled ? "🎵 Música: ON" : "🔇 Música: OFF";
        musicButton.classList.toggle("muted", !musicEnabled);
        musicButton.setAttribute("aria-pressed", String(musicEnabled));
    }
}

function initializeAudioControls() {
    const soundButton = document.getElementById("soundToggleButton");
    const musicButton = document.getElementById("musicToggleButton");
    if (soundButton) soundButton.addEventListener("click", () => setSoundEnabled(!soundEnabled));
    if (musicButton) musicButton.addEventListener("click", () => setMusicEnabled(!musicEnabled));
    updateAudioControls();
}

function playSelectSound() { playTone(520, 0.08, "sine", 0.035); }
function playValidMoveSound() { playTone(420, 0.08, "sine", 0.035); playTone(620, 0.12, "sine", 0.04, 0.07); }
function playInvalidMoveSound() { playTone(180, 0.14, "sawtooth", 0.025); }
function playMatchSound() { playTone(500, 0.08, "triangle", 0.04); playTone(700, 0.10, "triangle", 0.045, 0.07); playTone(900, 0.14, "triangle", 0.05, 0.14); }
function playCascadeSound(cascadeNumber) { const base = 500 + Math.min(cascadeNumber, 5) * 70; playTone(base, 0.08, "triangle", 0.045); playTone(base + 180, 0.10, "triangle", 0.05, 0.08); playTone(base + 360, 0.14, "triangle", 0.055, 0.16); }
function playSpecialCreatedSound() { playTone(650, 0.10, "triangle", 0.05); playTone(820, 0.12, "triangle", 0.055, 0.08); playTone(1000, 0.16, "triangle", 0.06, 0.18); }
function playRainbowCreatedSound() { playTone(523, 0.10, "triangle", 0.05); playTone(659, 0.10, "triangle", 0.05, 0.08); playTone(784, 0.12, "triangle", 0.055, 0.16); playTone(1046, 0.18, "triangle", 0.065, 0.25); }
function playSpecialActivateSound() { playTone(300, 0.08, "square", 0.045); playTone(550, 0.10, "triangle", 0.055, 0.07); playTone(900, 0.16, "triangle", 0.06, 0.15); }
function playSpecialComboSound() { playTone(250, 0.10, "square", 0.04); playTone(500, 0.12, "triangle", 0.05, 0.08); playTone(750, 0.14, "triangle", 0.055, 0.17); playTone(1100, 0.20, "sine", 0.065, 0.28); }
function playRainbowActivateSound() { playTone(300, 0.10, "sawtooth", 0.035); playTone(500, 0.10, "triangle", 0.045, 0.08); playTone(700, 0.10, "triangle", 0.05, 0.16); playTone(900, 0.12, "triangle", 0.055, 0.24); playTone(1200, 0.20, "sine", 0.065, 0.34); }
function playSuperRainbowSound() { playTone(220, 0.10, "square", 0.035); playTone(440, 0.12, "triangle", 0.045, 0.08); playTone(660, 0.14, "triangle", 0.05, 0.16); playTone(880, 0.16, "triangle", 0.06, 0.25); playTone(1320, 0.25, "sine", 0.07, 0.38); }
function playWinSound() { playTone(523, 0.14, "sine", 0.05); playTone(659, 0.14, "sine", 0.05, 0.13); playTone(784, 0.18, "sine", 0.06, 0.26); playTone(1046, 0.25, "sine", 0.065, 0.42); }
function playLoseSound() { playTone(400, 0.18, "sine", 0.04); playTone(300, 0.22, "sine", 0.04, 0.17); playTone(220, 0.28, "sine", 0.04, 0.34); }
function playObstacleSound(type = "generic") {
    const map = { box: 220, ice: 880, bomb: 140, rocket: 660, swap: 480, coin: 980 };
    const f = map[type] || map.generic || 360;
    playTone(f, 0.12, type === "bomb" ? "square" : "triangle", 0.045);
    playTone(f * 1.25, 0.13, "sine", 0.04, 0.06);
}

window.addEventListener("DOMContentLoaded", initializeAudioControls);
window.addEventListener("pagehide", stopMusic);
