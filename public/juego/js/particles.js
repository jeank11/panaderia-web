(function () {
    const MAX_PARTICLES = 70;
    const PARTICLE_LIFETIME = 620;

    function getBoardLayer() {
        const board = document.getElementById("board");
        if (!board) return null;

        let layer = board.querySelector(".particleLayer");
        if (!layer) {
            layer = document.createElement("div");
            layer.className = "particleLayer";
            board.appendChild(layer);
        }
        return layer;
    }

    function getCellCenter(row, col) {
        const board = document.getElementById("board");
        const cell = board?.querySelector(`.cell[data-row="${row}"][data-col="${col}"]`);
        if (!board || !cell) return null;

        const boardRect = board.getBoundingClientRect();
        const cellRect = cell.getBoundingClientRect();
        return {
            x: cellRect.left - boardRect.left + cellRect.width / 2,
            y: cellRect.top - boardRect.top + cellRect.height / 2
        };
    }

    function randomChoice(items) {
        return items[Math.floor(Math.random() * items.length)];
    }

    function spawnParticles(row, col, type = "sweet", amount = 6) {
        const layer = getBoardLayer();
        const center = getCellCenter(row, col);
        if (!layer || !center) return;

        while (layer.children.length > MAX_PARTICLES) {
            layer.firstElementChild?.remove();
        }

        const presets = {
            sweet: { symbols: ["✦", "•", "·"], className: "sweetParticle" },
            box: { symbols: ["▫", "▪", "✦"], className: "boxParticle" },
            ice: { symbols: ["✧", "❄", "·"], className: "iceParticle" },
            special: { symbols: ["✨", "✦", "⭐"], className: "specialParticle" },
            rainbow: { symbols: ["✦", "✧", "•"], className: "rainbowParticle" }
        };
        const preset = presets[type] || presets.sweet;

        for (let i = 0; i < amount; i++) {
            const particle = document.createElement("span");
            particle.className = `boardParticle ${preset.className}`;
            particle.textContent = randomChoice(preset.symbols);

            const angle = Math.random() * Math.PI * 2;
            const distance = 18 + Math.random() * 38;
            const size = 7 + Math.random() * 9;
            particle.style.left = `${center.x}px`;
            particle.style.top = `${center.y}px`;
            particle.style.fontSize = `${size}px`;
            particle.style.setProperty("--dx", `${Math.cos(angle) * distance}px`);
            particle.style.setProperty("--dy", `${Math.sin(angle) * distance - 12}px`);
            particle.style.setProperty("--rot", `${-80 + Math.random() * 160}deg`);
            particle.style.animationDuration = `${430 + Math.random() * 190}ms`;
            particle.style.animationDelay = `${Math.random() * 45}ms`;
            layer.appendChild(particle);

            window.setTimeout(() => particle.remove(), PARTICLE_LIFETIME);
        }
    }

    function spawnBurst(row, col, type, amount) {
        spawnParticles(row, col, type, amount);
        if (type === "special" || type === "rainbow") {
            window.setTimeout(() => spawnParticles(row, col, type, Math.max(3, Math.floor(amount / 2))), 90);
        }
    }

    function spawnBoardBurst(type = "sweet", amount = 18) {
        const layer = getBoardLayer();
        const board = document.getElementById("board");
        if (!layer || !board) return;

        const rect = board.getBoundingClientRect();
        const centerX = rect.width / 2;
        const centerY = rect.height / 2;
        for (let i = 0; i < amount; i++) {
            const particle = document.createElement("span");
            const symbols = type === "rainbow" ? ["✦", "✧", "🌈"] : ["✦", "✨", "•"];
            particle.className = `boardParticle ${type === "rainbow" ? "rainbowParticle" : "specialParticle"}`;
            particle.textContent = randomChoice(symbols);
            const angle = Math.random() * Math.PI * 2;
            const distance = 25 + Math.random() * Math.max(rect.width, rect.height) * 0.34;
            particle.style.left = `${centerX}px`;
            particle.style.top = `${centerY}px`;
            particle.style.fontSize = `${8 + Math.random() * 10}px`;
            particle.style.setProperty("--dx", `${Math.cos(angle) * distance}px`);
            particle.style.setProperty("--dy", `${Math.sin(angle) * distance}px`);
            particle.style.setProperty("--rot", `${-120 + Math.random() * 240}deg`);
            particle.style.animationDuration = `${520 + Math.random() * 220}ms`;
            layer.appendChild(particle);
            window.setTimeout(() => particle.remove(), PARTICLE_LIFETIME + 80);
        }
    }

    window.ParticleFX = {
        spawnParticles,
        spawnBurst,
        spawnBoardBurst
    };
})();
