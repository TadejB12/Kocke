<?php
session_start();

if (!isset($_SESSION['game'])) {
    header('Location: index.php');
    exit;
}

function izrisi_kocko($vrednost) {
    return "<img src='../slike/dice$vrednost.png' alt='Kocka $vrednost' width='40'>";  // Updated for red dice images
}

$results = $_SESSION['game']['results'];
$highest = max(array_column($results, 'total'));
$winners = array_filter($results, fn($r) => $r['total'] === $highest);
?>
<!DOCTYPE html>
<html lang="sl">
<head>
    <meta charset="UTF-8">
    <title>Rezultati igre</title>
    <link rel="stylesheet" href="../css/results.css">
    
</head>
<body>

<h2>Rezultati igre s kockami</h2>

<table>
    <thead>
        <tr>
            <th>Igralec</th>
            <?php foreach ($results[0]['rolls'] as $i => $_): ?>
                <th>Krog <?= $i + 1 ?></th>
            <?php endforeach; ?>
            <th>Skupna vsota</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $res): ?>
            <tr>
                <td data-label="Igralec"><strong><?= htmlspecialchars($res['player']) ?></strong></td>
                
                <?php foreach ($res['rolls'] as $i => $round): ?>
                    <td data-label="Krog <?= $i + 1 ?>">
                        <div class="dice-row">
                            <?php foreach ($round as $value): ?>
                                <?= izrisi_kocko($value) ?>
                            <?php endforeach; ?>
                        </div>
                    </td>
                <?php endforeach; ?>

                <td data-label="Skupna vsota"><strong><?= $res['total'] ?></strong></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<div class="winner">
    <h3>🏆 Zmagovalec/i:</h3>
    <?= implode(', ', array_map(fn($w) => htmlspecialchars($w['player']), $winners)) ?>
</div>

<p class="timer"><em>Preusmeritev nazaj na obrazec čez 10 sekund...</em></p>

<script>
        let timeRemaining = 10;
        const timerElement = document.querySelector('.timer');

        const countdown = setInterval(() => {
            timeRemaining--;
            timerElement.innerHTML = `<em>Preusmeritev nazaj na obrazec čez ${timeRemaining} sekund...</em>`;

            if (timeRemaining <= 0) {
                clearInterval(countdown);
                window.location.href = '../index.php';
            }
        }, 1000);
    </script>

</body>
</html>