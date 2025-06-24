<?php
session_start(); // Start de sessie om toegang te krijgen tot sessiegegevens

// Als de gebruiker niet is ingelogd, stuur hem door naar de loginpagina
if (!isset($_SESSION["user_id"])) {
    header("Location: ../index.php");
    exit;
}

include '../dbcon.php'; // Verbind met de database

// Haal alle scores op, inclusief gebruikersnaam, gesorteerd op hoogste score en snelste tijd
$stmt = $conn->prepare("
    SELECT u.username, s.score, s.completion_time 
    FROM scores s
    JOIN users u ON s.user_id = u.id
    ORDER BY s.score DESC, s.completion_time ASC
");
$stmt->execute();
$scores = $stmt->fetchAll(PDO::FETCH_ASSOC); // Sla alle scores op als associatieve array

// Haal de beste score van de ingelogde gebruiker op
$stmt = $conn->prepare("
    SELECT score, completion_time 
    FROM scores 
    WHERE user_id = :user_id 
    ORDER BY score DESC, completion_time ASC 
    LIMIT 1
");
$stmt->execute(['user_id' => $_SESSION["user_id"]]);
$userScore = $stmt->fetch(PDO::FETCH_ASSOC); // Sla de beste score van de gebruiker op
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Scorebord - Code Napoleon</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Arial', sans-serif;
            background-color: #121212;
            color: #fff;
            line-height: 1.6;
            overflow-x: hidden;
        }

        .container {
            max-width: 1000px;
            margin: 0 auto;
            padding: 20px;
            position: relative;
            min-height: 100vh;
        }

        header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid gold;
            margin-bottom: 30px;
        }

        h1 {
            color: gold;
            font-size: 2.5rem;
            margin-bottom: 10px;
        }

        .user-score {
            background: rgba(255, 215, 0, 0.1);
            border: 1px solid gold;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }

        .user-score h2 {
            color: gold;
            margin-bottom: 15px;
        }

        .score-value {
            font-size: 2rem;
            font-weight: bold;
            color: gold;
        }

        .time-value {
            font-family: monospace;
            font-size: 1.5rem;
        }

        .scoreboard-container {
            width: 100%;
            overflow-x: auto;
            margin-bottom: 40px;
            -webkit-overflow-scrolling: touch;
        }

        table.scoreboard {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            min-width: 600px;
        }

        table.scoreboard th {
            background-color: #333;
            color: gold;
            padding: 15px;
            text-align: center;
            position: sticky;
            top: 0;
        }

        table.scoreboard td {
            padding: 12px 15px;
            text-align: center;
            border-bottom: 1px solid #444;
        }

        table.scoreboard tr:nth-child(even) {
            background-color: rgba(255, 255, 255, 0.05);
        }

        table.scoreboard tr:hover {
            background-color: rgba(255, 215, 0, 0.1);
        }

        .highlight {
            background-color: rgba(255, 215, 0, 0.2) !important;
            font-weight: bold;
        }

        .medal {
            display: inline-block;
            width: 20px;
            height: 20px;
            margin-right: 10px;
            vertical-align: middle;
        }

        .gold { background-color: gold; }
        .silver { background-color: silver; }
        .bronze { background-color: #cd7f32; }

        .navigation {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #222;
            padding: 15px 0;
            text-align: center;
            z-index: 100;
            border-top: 1px solid gold;
        }

        .nav-link {
            display: inline-block;
            padding: 10px 20px;
            margin: 0 10px;
            background-color: gold;
            color: #121212;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }

        .nav-link:hover {
            background-color: #ffd700;
            transform: translateY(-2px);
        }

        @media (max-width: 768px) {
            .container {
                padding-bottom: 100px;
            }

            table.scoreboard th, 
            table.scoreboard td {
                padding: 10px 8px;
                font-size: 0.9rem;
            }

            .nav-link {
                padding: 8px 15px;
                margin: 0 5px;
                font-size: 0.9rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Scorebord</h1>
        </header>

        <?php if ($userScore): ?>
            <!-- Laat de beste score van de ingelogde gebruiker zien -->
            <div class="user-score">
                <h2>Jouw beste score</h2>
                <p>Score: <span class="score-value"><?= htmlspecialchars($userScore['score']) ?> punten</span></p>
                <p>Tijd: <span class="time-value"><?= gmdate("i:s", $userScore['completion_time']) ?></span></p>
            </div>
        <?php else: ?>
            <!-- Als de gebruiker nog geen score heeft -->
            <div class="user-score">
                <h2>Jouw score</h2>
                <p>Je hebt nog geen score. Speel het spel om een score te behalen!</p>
            </div>
        <?php endif; ?>

        <div class="scoreboard-container">
            <table class="scoreboard">
                <thead>
                    <tr>
                        <th>Positie</th>
                        <th>Gebruiker</th>
                        <th>Score</th>
                        <th>Tijd</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($scores as $index => $score): ?>
                        <tr <?= ($score['username'] === $_SESSION['username']) ? 'class="highlight"' : '' ?>>
                            <td>
                                <!-- Geef medailles voor top 3 -->
                                <?php if ($index === 0): ?>
                                    <span class="medal gold"></span>
                                <?php elseif ($index === 1): ?>
                                    <span class="medal silver"></span>
                                <?php elseif ($index === 2): ?>
                                    <span class="medal bronze"></span>
                                <?php endif; ?>
                                <?= $index + 1 ?>
                            </td>
                            <td><?= htmlspecialchars($score['username']) ?></td>
                            <td><?= htmlspecialchars($score['score']) ?></td>
                            <td class="time-value"><?= gmdate("i:s", $score['completion_time']) ?></td>
                        </tr>
                    <?php endforeach; ?>

                    <?php if (empty($scores)): ?>
                        <tr>
                            <td colspan="4">Nog geen scores beschikbaar</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Navigatie onderaan -->
    <div class="navigation">
        <a href="../room_1.php" class="nav-link">Opnieuw spelen</a>
        <a href="../index.php" class="nav-link">Terug naar start</a>
    </div>
</body>
</html>