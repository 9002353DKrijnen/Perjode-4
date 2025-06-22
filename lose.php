<?php
session_start();

// Controleer of gebruiker ingelogd is
if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit;
}

// Bewaar gebruikersgegevens tijdelijk
$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

// Bereken gebruikte tijd
$timeUsed = isset($_SESSION['start_time']) ? time() - $_SESSION['start_time'] : 0;

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
    <title>Game Over</title>
    <style>
        body {
            background-color: #8B0000;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
            padding: 20px;
            background-image: radial-gradient(circle, #600000, #8B0000, #600000);
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: rgba(139, 0, 0, 0.7);
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.5);
            border: 2px solid #FF0000;
        }
        h1 {
            color: #FFD700;
            font-size: 2.5em;
            text-shadow: 2px 2px 4px #000;
        }
        .time-display {
            font-family: monospace;
            font-size: 1.2em;
            background-color: rgba(0, 0, 0, 0.3);
            padding: 10px;
            border-radius: 5px;
            display: inline-block;
            margin: 10px 0;
        }
        a {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #FFD700;
            color: #8B0000;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            transition: all 0.3s;
        }
        a:hover {
            background-color: #FF0000;
            color: white;
            transform: scale(1.05);
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
        <div class="icon">💀</div>
        <h1>⏳ Tijd is om!</h1>
        <p>Je hebt niet op tijd alle vragen beantwoord.</p>
        
        <div class="time-display">
            Je tijd: <?php echo floor($timeUsed / 60) . " min " . ($timeUsed % 60) . " sec"; ?>
        </div>

        <p>Probeer het opnieuw om te ontsnappen!</p>
        <a href="room_1.php">Opnieuw proberen</a>
        <br>
        <a href="index.php">Terug naar start</a>
    </div>
</body>
</html>