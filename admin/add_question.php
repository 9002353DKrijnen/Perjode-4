<?php
session_start();
include '../dbcon.php';  // Verbind met database

// Check of admin is ingelogd, anders redirect naar login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$error = '';   // Om foutmeldingen op te slaan
$success = ''; // Om succesmeldingen op te slaan

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Gegevens uit formulier halen en trimmen (spaties weg)
    $question = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $hint = trim($_POST['hint']);
    $roomId = (int)$_POST['roomId'];  // Zorg dat roomId een integer is

    // Validatie: vraag, antwoord en roomId moeten ingevuld zijn
    if (empty($question) || empty($answer) || empty($roomId)) {
        $error = "Vraag, antwoord en room ID zijn verplicht!";
    } else {
        try {
            // Query voorbereiden om nieuwe vraag toe te voegen
            $stmt = $conn->prepare("INSERT INTO questions (question, answer, hint, roomId) VALUES (?, ?, ?, ?)");
            $stmt->execute([$question, $answer, $hint, $roomId]);
            $success = "Vraag succesvol toegevoegd!";
        } catch (PDOException $e) {
            // Fout bij uitvoeren query
            $error = "Fout bij toevoegen: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Vraag Toevoegen</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 600px; margin: 0 auto; padding: 20px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input, textarea { width: 100%; padding: 8px; }
        textarea { height: 100px; }
        button { padding: 10px 15px; background: #333; color: white; border: none; cursor: pointer; }
        .error { color: red; }
        .success { color: green; }
    </style>
</head>
<body>
    <h1>Vraag Toevoegen</h1>

    <!-- Toon foutmelding indien aanwezig -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Toon succesmelding indien aanwezig -->
    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Formulier om vraag toe te voegen -->
    <form method="post">
        <div class="form-group">
            <label for="question">Vraag:</label>
            <textarea id="question" name="question" required></textarea>
        </div>

        <div class="form-group">
            <label for="answer">Antwoord:</label>
            <input type="text" id="answer" name="answer" required>
        </div>

        <div class="form-group">
            <label for="hint">Hint (optioneel):</label>
            <textarea id="hint" name="hint"></textarea>
        </div>

        <div class="form-group">
            <label for="roomId">Room ID:</label>
            <input type="number" id="roomId" name="roomId" required>
        </div>

        <button type="submit">Toevoegen</button>
    </form>

    <p><a href="index.php">Terug naar overzicht</a></p>
</body>
</html>
