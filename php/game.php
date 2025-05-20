<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $players = [];
    
    // Dynamically collect all player inputs (keys starting with 'player')
    foreach ($_POST as $key => $value) {
        if (preg_match('/^player[1-5]$/', $key) && !empty($value)) {
            $players[] = trim($value);
        }
    }

    $numRounds = (int) ($_POST['numRounds'] ?? 1);
    $numDice = (int) ($_POST['numDice'] ?? 1);

    $_SESSION['game'] = [
        'players' => [],
        'numRounds' => $numRounds,
        'numDice' => $numDice,
        'results' => [],
    ];

    foreach ($players as $name) {
        $_SESSION['game']['players'][] = $name;
    }

    // Simulate the game
    foreach ($_SESSION['game']['players'] as $player) {
        $totalScore = 0;
        $diceRolls = [];

        for ($r = 0; $r < $numRounds; $r++) {
            $roundRolls = [];
            for ($d = 0; $d < $numDice; $d++) {
                $roll = rand(1, 6);
                $roundRolls[] = $roll;
                $totalScore += $roll;
            }
            $diceRolls[] = $roundRolls;
        }

        $_SESSION['game']['results'][] = [
            'player' => $player,
            'rolls' => $diceRolls,
            'total' => $totalScore,
        ];
    }

    // Redirect to results page
    header('Location: results.php');
    exit;
}
?>