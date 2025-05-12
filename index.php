<!DOCTYPE html>
<html lang="sl">
<head>
    <title>Gambling room</title>
    <meta charset="utf-8">
    <link rel="shortcut icon" type="x-icon" href="slike/icon.png">
    <link rel="stylesheet" href="css/style.css">
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>
<body>
    <div id="hidden" onClick="vizitka()"></div>

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
</body>
</html>
