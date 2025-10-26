document.addEventListener("DOMContentLoaded", () => {
	const canvas = document.getElementById("squares-canvas");
	const ctx = canvas.getContext("2d");


	let direction = canvas.dataset.direction || "right";
	const speed = parseFloat(canvas.dataset.speed) || 1;
	const borderColor = canvas.dataset.borderColor || "#666";
	const hoverFillColor = canvas.dataset.hoverFill || "#ff5555";
	const squareSize = parseInt(canvas.dataset.squareSize) || 40;

	let gridOffset = { x: 0, y: 0 };
	let lastTime = 0;
	let mousePos = { x: null, y: null };
	let hovered = null;
	const movingSquares = []; // cases cliquées

	const wrap = (v, m) => ((v % m) + m) % m;

	// Resize responsive
	function resizeCanvas() {
		canvas.width = canvas.offsetWidth;
		canvas.height = canvas.offsetHeight;
	}
	window.addEventListener("resize", resizeCanvas);
	resizeCanvas();

	canvas.addEventListener("mousemove", (e) => {
		const r = canvas.getBoundingClientRect();
		mousePos.x = e.clientX - r.left;
		mousePos.y = e.clientY - r.top;
	});
	canvas.addEventListener("mouseleave", () => (mousePos = { x: null, y: null }));

	// Clic : ajoute une case à la liste des cases mobiles
	canvas.addEventListener("click", () => {
		if (!hovered) return;
		movingSquares.push({
		x: hovered.x,
		y: hovered.y
		});
	});

	// Fonction principale
	function draw(ts) {
		const delta = (ts - lastTime) / 16.67;
		lastTime = ts;

		const move = speed * delta * 0.5;

		//Mouvement de la grille
		switch (direction) {
		case "right": gridOffset.x -= move; break;
		case "left":  gridOffset.x += move; break;
		case "up":    gridOffset.y += move; break;
		case "down":  gridOffset.y -= move; break;
		case "diagonal":
			gridOffset.x -= move;
			gridOffset.y -= move;
			break;
		}

		// phase d’affichage (calcul du décalage)
		const phaseX = -wrap(gridOffset.x, squareSize);
		const phaseY = -wrap(gridOffset.y, squareSize);

		// Case survolée
		if (mousePos.x != null && mousePos.y != null) {
		const i = Math.floor((mousePos.x - phaseX) / squareSize);
		const j = Math.floor((mousePos.y - phaseY) / squareSize);
		hovered = {
			i, j,
			x: i * squareSize + phaseX,
			y: j * squareSize + phaseY
		};
		} else hovered = null;

		ctx.clearRect(0, 0, canvas.width, canvas.height);
		ctx.strokeStyle = borderColor;

		// Grille animée
		const startX = phaseX - squareSize;
		const startY = phaseY - squareSize;

		for (let x = startX; x < canvas.width; x += squareSize) {
		for (let y = startY; y < canvas.height; y += squareSize) {
			const i = Math.floor((x - phaseX) / squareSize);
			const j = Math.floor((y - phaseY) / squareSize);

			if (hovered && hovered.i === i && hovered.j === j) {
			ctx.fillStyle = hoverFillColor;
			ctx.fillRect(x, y, squareSize - 1, squareSize - 1);
			}

			ctx.strokeRect(x + 0.5, y + 0.5, squareSize - 1, squareSize - 1);
		}
		}

		// Cases cliquées → avancent dans le même sens visuel que la grille
		for (const sq of movingSquares) {
		switch (direction) {
			case "right": sq.x += move; break;   // ⬅️ inversé
			case "left":  sq.x -= move; break;
			case "up":    sq.y -= move; break;
			case "down":  sq.y += move; break;
			case "diagonal":
			sq.x += move;
			sq.y += move;
			break;
		}

		if (
			sq.x + squareSize > 0 &&
			sq.y + squareSize > 0 &&
			sq.x < canvas.width &&
			sq.y < canvas.height
		) {
			ctx.fillStyle = hoverFillColor;
			ctx.fillRect(sq.x, sq.y, squareSize - 1, squareSize - 1);
		}
		}

		// Dégradé radial doux
		const gradient = ctx.createRadialGradient(
		canvas.width / 2, canvas.height / 2, 0,
		canvas.width / 2, canvas.height / 2,
		Math.sqrt(canvas.width ** 2 + canvas.height ** 2) / 2
		);
		gradient.addColorStop(0, "rgba(0,0,0,0)");
		gradient.addColorStop(1, "rgba(0,0,0,0.25)");
		ctx.fillStyle = gradient;
		ctx.fillRect(0, 0, canvas.width, canvas.height);

		requestAnimationFrame(draw);
	}

	requestAnimationFrame(draw);
});
