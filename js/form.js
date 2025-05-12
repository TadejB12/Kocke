let playerCount = 2;
const maxPlayers = 5;
const minPlayers = 2;

const addBtn = document.querySelector('button[onclick="addPlayer()"]');
const removeBtn = document.querySelector('button[onclick="removePlayer()"]');

function updateButtonStates() {
    addBtn.disabled = playerCount >= maxPlayers;
    removeBtn.disabled = playerCount <= minPlayers;
}

function addPlayer() {
    if (playerCount >= maxPlayers) return;

    playerCount++;
    const container = document.getElementById("playerContainer");

    const div = document.createElement("div");
    div.className = "players";
    div.innerHTML = ` 
            <label for="player${playerCount}">PLAYER ${playerCount}</label>
            <input type="text" id="player${playerCount}" name="player${playerCount}" maxlength="10" required>
        `;
    container.appendChild(div);

    updateButtonStates();
}

function removePlayer() {
    if (playerCount <= minPlayers) return;

    const container = document.getElementById("playerContainer");
    container.removeChild(container.lastElementChild);
    playerCount--;

    updateButtonStates();
}

function validatePlayerNames() {
    const playerInputs = document.querySelectorAll('input[id^="player"]');
    const playerNames = [];

    // Loop through all the player input fields and collect the names
    for (let input of playerInputs) {
        const playerName = input.value.trim();
        if (playerName === '') {
            alert("Please enter a name for each player.");
            return false; // Prevent form submission
        }
        // Check if the name already exists
        if (playerNames.includes(playerName)) {
            swal({
                title: 'Napaka:',
                text: 'Igralci morajo imeti različna imena.',
                icon: 'error',
                button: {
                    text: 'OK',
                }
            });
            return false; // Prevent form submission
        }

        // Add the player name to the list for comparison
        playerNames.push(playerName);
    }

    return true; // Proceed with form submission if no issues
}


// Initialize button states on page load
window.onload = updateButtonStates;

function vizitka(){
  swal({
      title: 'Made by:',
      text: 'Tadej Bensa 4. RB',
      icon: 'info',
      button: {
          text: 'OK',
      }
  });
}

