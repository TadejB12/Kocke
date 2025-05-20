<?php
session_start();

if (!isset($_SESSION['game'])) {
    header('Location: index.php');
    exit;
}

function izrisi_kocko($vrednost) {
    return "<div class='dice' data-final='$vrednost'>
            <img src='../slike/dice$vrednost.png' alt='Kocka $vrednost' width='40'>
        </div>";
    //return "<img src='../slike/dice$vrednost.png' alt='Kocka $vrednost' width='40'>";  // Updated for red dice images
}


$results = $_SESSION['game']['results'];
usort($results, fn($a, $b) => $b['total'] <=> $a['total']);
$playerCount = count($results);
$podium = array_slice($results, 0, min(3, $playerCount));
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Rezultati igre</title>
    <link rel="stylesheet" href="../css/results.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
</head>
<body>

<h2>Rezultati igre s kockami</h2>
<audio id="confettiSound" src="../audio/first_place.mp3"></audio>
<audio id="backgroundMusic" src=" ../audio/background_music.mp3" loop></audio>
<audio id="diceSound" src="../audio/dice.mp3"></audio>
<table>
    <thead>
    <tr>
        <th>Igralec</th>
        <?php if (!empty($results)): ?>
            <?php foreach ($results[0]['rolls'] as $i => $round): ?>
                <th>Krog <?= $i + 1 ?></th>
            <?php endforeach; ?>
        <?php endif; ?>
        <th>Skupna vsota</th>
    </tr>
</thead>
    <tbody>
    <?php foreach ($results as $res): ?>
    <tr>
        <td data-label="Igralec"><?= htmlspecialchars($res['player']) ?></td>

        <?php foreach ($res['rolls'] as $i => $round): ?>
            <td data-label="Krog <?= $i + 1 ?>">
                <div class="dice-row round-<?= $i + 1 ?>" style=" visibility: hidden;  opacity: 0;">
                    <?php foreach ($round as $value): ?>
                        <?= izrisi_kocko($value) ?>
                    <?php endforeach; ?>
                </div>
            </td>
        <?php endforeach; ?>

        <td data-label="Skupna vsota"class="final-total hidden" style=" visibility: hidden;  opacity: 0;"><?= $res['total'] ?></td>
    </tr>
    <?php endforeach; ?>
</tbody>
</table>

<div class="podium">
    <h3>🏆 Podij zmagovalcev</h3>
    <div class="podium-container players-<?= count($podium) ?>">
        <?php
        if (count($podium) >= 3) {
            // Za 3 ali več: 2nd (left), 1st (center), 3rd (right)
            $visual_order = [
                1 => $podium[1] ?? null,  // 2nd
                0 => $podium[0],          // 1st
                2 => $podium[2] ?? null   // 3rd
            ];
        } else {
            // Za 1 ali 2 igralca: naravno zaporedje
            $visual_order = $podium;
        }

        foreach ($visual_order as $index => $player):
            if (!$player) continue;
            $place = $index + 1;
            $medals = ['🥇', '🥈', '🥉'];
        ?>
            <div class="podium-place place-<?= $place ?>" id="place-<?= $place ?>">
                <div class="medal"><?= $medals[$index] ?? '' ?></div>
                <strong><?= htmlspecialchars($player['player']) ?></strong>
                <div class="score"><?= $player['total'] ?> točk</div>
            </div>
        <?php endforeach; ?>
    </div>

    <?php if ($playerCount > 3): ?>
        <div class="honorable-mentions" style=" visibility: hidden; opacity: 0;">
            <h4>🎖️ Ostali udeleženci</h4>
            <ul>
                <?php foreach (array_slice($results, 3) as $player): ?>
                    <li><?= htmlspecialchars($player['player']) ?> — <?= $player['total'] ?> točk</li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>
</div>

<p class="timer"><em>Preusmeritev nazaj na obrazec čez 30 sekund...</em></p>


<script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.6.0/dist/confetti.browser.min.js"></script>
<script>
    let timeRemaining = 30;
    const timerElement = document.querySelector('.timer');

    const countdown = setInterval(() => {
        timeRemaining--;
        timerElement.innerHTML = `<em>Preusmeritev nazaj na obrazec čez ${timeRemaining} sekund...</em>`;
        if (timeRemaining <= 0) {
            clearInterval(countdown);
            window.location.href = '../index.php';
        }
    }, 1000);

    const rollDuration = 1600;
    const rollInterval = 200;
    const totalFrames = Math.floor(rollDuration / rollInterval);
    const diceImagesPath = '../slike/dice';
    const playerCount = <?= $playerCount ?>;

    let currentRound = 1;
    const maxRound = document.querySelectorAll('.dice-row').length > 0 
        ? Math.max(...Array.from(document.querySelectorAll('.dice-row')).map(d => {
            const classes = [...d.classList];
            const roundClass = classes.find(c => c.startsWith('round-'));
            return roundClass ? parseInt(roundClass.split('-')[1]) : 0;
        })) 
        : 0;

function animateRound(round) {
    const diceSound = document.getElementById('diceSound');
    diceSound.currentTime = 1.2;
    diceSound.play();

    const diceRows = document.querySelectorAll(`.round-${round}`);
    if (!diceRows.length) return;

    // Hide all rounds
    document.querySelectorAll('.dice-row').forEach(row => {
        row.classList.remove('visible');
    });

    // Show current round rows
    diceRows.forEach(row => {
        row.style.visibility = 'visible';
        row.style.opacity = '0';
        void row.offsetWidth;

        row.classList.add('visible', 'animate__animated', 'animate__fadeIn');
    });

    let animations = [];

    diceRows.forEach(row => {
        const diceInRound = row.querySelectorAll('.dice');

        diceInRound.forEach(dice => {
            let finalValue = parseInt(dice.getAttribute('data-final'), 10);
            let img = dice.querySelector('img');
            let frame = 0;

            const roll = setInterval(() => {
                let randomFace = Math.floor(Math.random() * 6) + 1;
                img.src = `${diceImagesPath}${randomFace}.png`;
                img.alt = `Rolling dice ${randomFace}`;
                frame++;
                if (frame >= totalFrames) {
                    clearInterval(roll);
                    img.src = `${diceImagesPath}${finalValue}.png`;
                    img.alt = `Dice ${finalValue}`;
                }
            }, rollInterval);

            animations.push(new Promise(resolve => setTimeout(resolve, rollDuration)));
        });
    });

    Promise.all(animations).then(() => {
    currentRound++;
    if (currentRound <= maxRound) {
        animateRound(currentRound);
    } else {
        // All rounds done, show all dice rows permanently
        document.querySelectorAll('.dice-row').forEach(row => {
            row.classList.add('visible');
        });
        // Show final totals
        document.querySelectorAll('.final-total').forEach(cell => {
            cell.style.visibility = 'visible';
            cell.style.opacity = '0';
            void cell.offsetWidth;

            cell.classList.add('visible', 'animate__animated', 'animate__fadeInUp');
        });
        
        setTimeout(() => {
        animatePodium();
        },1500);
    }
});
}

// Start animation with round 1
animateRound(currentRound);

function animatePodium() {
    const places = [
        document.getElementById('place-3'),
        document.getElementById('place-2'),
        document.getElementById('place-1')
    ];

    let delay = 0;
    let lastAnimationTime = 0;

    places.forEach((el, i) => {
        if (!el) return;

        setTimeout(() => {
            el.classList.add('animate__animated', 'animate__bounceInUp');
            el.style.opacity = 1;

            if (el.id === 'place-1') {
                // Trigger confetti slightly after animation starts
                
                setTimeout(() => {
                    confetti({
                        particleCount: 250,
                        spread: 70,
                        origin: { y: Math.min(0.6 + (playerCount - 2) * 0.1, 1) }
                    });
                    document.getElementById('confettiSound').play();
                }, 1000);
                
            }
        }, delay);

        delay += 1000;
        lastAnimationTime = delay;
    });

    setTimeout(() => {
        const mentions = document.querySelector('.honorable-mentions');
        if (mentions) {
            mentions.style.visibility = 'visible';
            mentions.style.opacity = '0';
            void mentions.offsetWidth;
            mentions.classList.add('visible', 'animate__animated', 'animate__fadeInUp');
        }
        const music=document.getElementById('backgroundMusic')
        music.volume = 0.3;
        music.play();
    }, lastAnimationTime + 2000);
   
}
</script>

</body>
</html>