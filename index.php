<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Speed Clicking</title>
  <meta name="description" content="The HTML5 Herald">
  <meta name="author" content="SitePoint">
  <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
  <svg id="container" width="100%" height="100%">
    <defs>
      <radialGradient id="gradient" cx="50%" cy="50%" r="50%" fx="50%" fy="50%">
        <stop offset="75%" style="stop-color: #000;" />
        <stop offset="100%" style="stop-color: #15F4EE;" />
      </radialGradient>
    </defs>
    <text id="score" x="20" y="50" text-anchor="left" fill="white">Score: 0</text>
    <text class="start-text one" x="50%" y="50%" text-anchor="middle">READY</text>
    <text class="start-text two" x="50%" y="50%" text-anchor="middle">STEADY</text>
    <text class="start-text three" x="50%" y="50%" text-anchor="middle">GO!</text>
  </svg>
  <div id="overlay">
    <div id="game-over">Game Over</div>
    <div id="play" onclick="startGame()">Play</div>
  <div>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.0/anime.min.js"></script>
  <script>

    const container = document.getElementById("container");
    const overlay = document.getElementById("overlay");
    const scoreText = document.getElementById("score");
    const gameOverText = document.getElementById("game-over");
    gameOverText.innerHTML = gameOverText.textContent.replace(/\S/g, "<span class='letter'>$&</span>")
    const playButton = document.getElementById("play");

    // Score
    let score = 0;

    /**************************************************************************
    * Start Game -- Starts a new game
    **************************************************************************/
    function startGame() {
      let timeline = anime.timeline({ easing: "easeInExpo" });
      timeline
      .add({ begin: () => {
        overlay.style.display = "none";
        playButton.style.display = "none";
        // Reset score
        score = 0;
        scoreText.textContent = `Score: ${score}`;
      }})
      .add({ targets: ".start-text.one", opacity: [0, 1], scale: [0.2, 1], duration: 800 })
      .add({ targets: ".start-text.one", opacity: [1, 0], scale: 2, duration: 500, delay: 100 })
      .add({ targets: ".start-text.two", opacity: [0, 1], scale: [0.2, 1], duration: 800 })
      .add({ targets: ".start-text.two", opacity: [1, 0], scale: 2, duration: 500, delay: 100 })
      .add({ targets: ".start-text.three", opacity: [0, 1], scale: [0.2, 1], duration: 800 })
      .add({ targets: ".start-text.three", opacity: [1, 0], scale: 2, duration: 500, delay: 100 })
      .add({ complete: () => createCircle() });
    }

    /**************************************************************************
    * End Game -- Handle game over
    **************************************************************************/
    function endGame() {
      let timeline = anime.timeline({ easing: "easeOutExpo" });
      timeline
      .add({ begin: () => overlay.style.display = "flex" })
      .add({ targets: ".letter", scale: [3, 1], opacity: [0, 1], delay: anime.stagger(50),
        begin: () => gameOverText.style.display = "block" })
      .add({ targets: gameOverText, translateY: [0, 50], opacity: [1, 0],
        complete: () => gameOverText.style.display = "none" })
      .add({ targets: playButton, opacity: [0, 1],
        begin: () => playButton.style.display = "block" })
    }

    /**************************************************************************
    * Create targets to click
    **************************************************************************/
    function createCircle() {
      // Create a circle
      const svgns = "http://www.w3.org/2000/svg";
      let circle = document.createElementNS(svgns, "circle");
      circle.setAttributeNS(null, 'cx', `${Math.random() * 90 + 5}%`);
      circle.setAttributeNS(null, 'cy', `${Math.random() * 80 + 10}%`);
      // Handle circle on click
      circle.addEventListener("click", function(event) {
        // Remove circle and animation
        anime.remove(this);
        this.remove();
        // Update score
        score += 1;
        scoreText.textContent = `Score: ${score}`;
        // Create next circle
        createCircle();
      });
      container.appendChild(circle);

      // Circle animation
      anime({ targets: circle, r: [0, 25], easing: "linear", direction: 'alternate',
        duration: 1000 - Math.floor(score / 20) * 50,
        complete: animation => {
          circle.remove();
          endGame();
        }
      });
    }
  </script>
</body>
</html>
