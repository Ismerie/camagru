// Settings
const updateRate = 1000 / 60;
let lastTime = 0;
let elapsedTime = 0;

// Canvas setup
const canvas = document.getElementById("blobContainer");
const ctx = canvas.getContext("2d");
resizeCanvas();

// Calculate canvas dimensions
function resizeCanvas() {
    canvas.width = window.innerWidth;
    canvas.height = window.innerHeight;
}

// FPS calculation
let fps = 0;
let accumulatedTime = 0;
let accumulatedFrames = 0;

// Circle properties
const numCircles = 15;
const circles = [];
const velocity = 1;
const size = Math.min(canvas.width, canvas.height) / 5;

// Initialize circles
function initializeCircles() {
    for (let i = 0; i < numCircles; i++) {
        const randSize = Math.random() * size + 10;

        const gradient = ctx.createRadialGradient(
            canvas.width / 2,
            0,
            0,
            canvas.width / 2,
            0,
            Math.max(canvas.height, canvas.width)
        );
        console.log("hey")
        gradient.addColorStop(0, "hsl(212,13%,25%)");
        gradient.addColorStop(0.5, "hsl(213,13%,36%)");
        gradient.addColorStop(1, "hsl(213,13%,54%)");

        circles.push({
            size: randSize,
            gradient: gradient,
            x: (Math.random() - 0.5) * canvas.width + canvas.width / 2,
            y: (Math.random() - 0.5) * canvas.height + canvas.height / 2,
            vx: (Math.random() - 0.5) * velocity * 1,
            vy: (Math.random() - 0.5) * velocity * 1
        });
    }
}

// Main loop
function main(timestamp) {
    resizeCanvas();

    accumulatedTime += timestamp - lastTime;
    accumulatedFrames++;

    elapsedTime += timestamp - lastTime;
    lastTime = timestamp;

    if (accumulatedTime >= 1000) {
        fps = accumulatedFrames;
        accumulatedFrames = 0;
        accumulatedTime -= 1000;
    }

    while (elapsedTime >= updateRate) {
        update();
        elapsedTime -= updateRate;
    }

    render();
    requestAnimationFrame(main);
}

// Update circle positions
function update() {
    for (const circle of circles) {
        circle.x += circle.vx;
        circle.y += circle.vy;

        // Horizontal Boundaries
        if (circle.x <= circle.size || circle.x >= canvas.width - circle.size) {
            circle.vx *= -1;
            circle.x =
                circle.x <= circle.size
                    ? circle.size
                    : canvas.width - circle.size;
        }

        //Vertical Boundaries
        if (
            circle.y <= circle.size ||
            circle.y >= canvas.height - circle.size
        ) {
            circle.vy *= -1;
            circle.y =
                circle.y <= circle.size
                    ? circle.size
                    : canvas.height - circle.size;
        }
    }
}

// Render circles
function render() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    for (const circle of circles) {
        ctx.fillStyle = circle.gradient;
        ctx.beginPath();
        ctx.arc(circle.x, circle.y, circle.size, 0, 2 * Math.PI);
        ctx.fill();
    }
}

// Initialize and start
initializeCircles();
requestAnimationFrame(main);
