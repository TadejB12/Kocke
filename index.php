<!DOCTYPE html>
<html lang="sl">
<head>
    <title>Gambling room</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" type="x-icon" href="slike/icon.png">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    <style>
        #musicToggle {
            position: fixed;
            bottom: 20px;
            right: 20px;
            font-size: 1em;
            font-weight: bold;
            color: white;
            background-color: #E30101;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.15);
            transition: background-color 0.3s, transform 0.2s;
            z-index: 1000;
            width:100px;
            height: 50px;
        }

        #musicToggle:hover {
            background-color: #aa0707;
            transform: scale(1.05);
        }
    </style>

</head>
<body>
    <div id="hidden" onClick="vizitka()"></div>
    <audio id="backgroundMusic" src="audio/background_music.mp3" loop></audio>
    <button id="musicToggle">🔈 Mute</button>

    <form name="Obrazec" id="Obrazec" method="post" autocomplete="off" action="php/game.php" onsubmit="return validatePlayerNames()">
        <div id="Header">
            <h1 class="naslov">Gambling Dice</h1>
        </div>
        
        <div id="wrapper">
            <div id="playerContainer">
                <div class="players">
                    <label for="player1">PLAYER 1</label>
                    <input type="text" id="player1" name="player1" maxlength="10" required>
                </div>
                <div class="players">
                    <label for="player2">PLAYER 2</label>
                    <input type="text" id="player2" name="player2" maxlength="10" required>
                </div>
            </div>

            <div class="player-controls">
                <button type="button" onclick="addPlayer()">Add Player</button>
                <button type="button" onclick="removePlayer()">Remove Player</button>
            </div>

            <div class="gameStats">
                <label for="numRounds">ROUNDS:</label>
                <select name="numRounds" id="numRounds">
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                    <option value="4">Four</option>
                    <option value="5">Five</option>
                </select>

                <label for="numDice">DICE:</label>
                <select name="numDice" id="numDice">
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                </select>
            </div>

            <input id="start" type="submit" value="Start">
        </div>
    </form>
    

    
    <script src = "js/form.js"></script>
    <script>
    const music = document.getElementById('backgroundMusic');
    const toggleBtn = document.getElementById('musicToggle');

    music.volume = 0.3;
    let musicStarted = false;

    // Play music on first user interaction
    const startMusic = () => {
        if (!musicStarted) {
            music.play().then(() => {
                musicStarted = true;
            }).catch(err => {
                console.warn("Music autoplay was blocked:", err);
            });
        }
    };

    document.addEventListener('click', startMusic, { once: true });

    // Mute/unmute toggle
    toggleBtn.addEventListener('click', () => {
        music.muted = !music.muted;
        toggleBtn.textContent = music.muted ? '🔇 Unmute' : '🔈 Mute';
    });
    </script>
</body>
</html>