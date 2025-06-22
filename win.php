<?php
session_start();

// Controleer of gebruiker ingelogd is
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

include 'dbcon.php';

// Bewaar gebruikersgegevens tijdelijk
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Bereken score en tijd
$timeUsed = isset($_SESSION['start_time']) ? time() - $_SESSION['start_time'] : 0;
$score = max(50, 100 - floor($timeUsed / 10)); // Minimaal 50 punten

// Sla score op in database
try {
    $stmt = $conn->prepare("INSERT INTO scores (user_id, score, completion_time) VALUES (:user_id, :score, :time)");
    $stmt->execute([
        'user_id' => $user_id,
        'score' => $score,
        'time' => $timeUsed
    ]);
} catch (PDOException $e) {
    error_log("Score opslaan mislukt: " . $e->getMessage());
}

// Wis alleen spelgerelateerde sessievariabelen
unset($_SESSION['start_time']);
unset($_SESSION['questionIndex']);
unset($_SESSION['questionIndex2']);

// Zet gebruikersgegevens terug
$_SESSION['user_id'] = $user_id;
$_SESSION['username'] = $username;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Je bent ontsnapt!</title>
    <style>
        body {
            background-color: #006400;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 20px;
            background-image: radial-gradient(circle, #004d00, #006400, #004d00);
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(0, 100, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            border: 2px solid #00FF00;
        }
        h1 {
            color: gold;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px #000;
        }
        .score-display {
            font-size: 1.5em;
            margin: 20px 0;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 15px;
            border-radius: 8px;
        }
        .time-display {
            font-family: monospace;
            font-size: 1.2em;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: gold;
            color: #006400;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        a:hover {
            background-color: #ffd700;
            transform: scale(1.05);
        }
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: gold;
            opacity: 0;
            animation: confetti 5s ease-in-out infinite;
        }
        @keyframes confetti {
            0% { transform: translateY(0) rotate(0deg); opacity: 1; }
            100% { transform: translateY(100vh) rotate(360deg); opacity: 0; }
        }
        .icon {
            font-size: 4em;
            margin: 20px;
            animation: pulse 1.5s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="icon">🏆</div>
        <h1>🎉 Gefeliciteerd!</h1>
        <p>Je hebt alle vragen goed beantwoord en bent ontsnapt!</p>
        
        <div class="score-display">
            <p>Je score: <strong><?php echo $score; ?> punten</strong></p>
            <p>Je tijd: <span class="time-display"><?php 
                echo floor($timeUsed / 60) . " min " . ($timeUsed % 60) . " sec"; 
            ?></span></p>
        </div>

        <p>Bekijk je prestatie op het <a href="user/score.php">scorebord</a></p>
        <a href="room_1.php">Opnieuw spelen</a>
        <br>
        <a href="index.php">Terug naar start</a>
    </div>

    <script>
        // Confetti effect
        for (let i = 0; i < 50; i++) {
            createConfetti();
        }

        function createConfetti() {
            const confetti = document.createElement('div');
            confetti.className = 'confetti';
            confetti.style.left = Math.random() * 100 + 'vw';
            confetti.style.backgroundColor = `hsl(${Math.random() * 60 + 30}, 100%, 50%)`;
            confetti.style.width = Math.random() * 10 + 5 + 'px';
            confetti.style.height = confetti.style.width;
            confetti.style.animationDuration = Math.random() * 3 + 2 + 's';
            confetti.style.animationDelay = Math.random() * 2 + 's';
            document.body.appendChild(confetti);
        }
    </script>
</body>
</html>