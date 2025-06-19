<?php
session_start();
include '../dbcon.php';

// Check of admin is ingelogd, anders redirect naar login
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

$error = '';
$success = '';

// Check of er een id is opgegeven via GET
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id']; // Zorg dat id een integer is

// Haal de bestaande vraag op uit de database
$stmt = $conn->prepare("SELECT * FROM questions WHERE id = ?");
$stmt->execute([$id]);
$question = $stmt->fetch(PDO::FETCH_ASSOC);

// Als de vraag niet bestaat, redirect terug
if (!$question) {
    header("Location: index.php");
    exit;
}

// Verwerk het formulier als het is verzonden
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $questionText = trim($_POST['question']);
    $answer = trim($_POST['answer']);
    $hint = trim($_POST['hint']);
    $roomId = (int)$_POST['roomId'];

    // Check verplichte velden
    if (empty($questionText) || empty($answer) || empty($roomId)) {
        $error = "Vraag, antwoord en room ID zijn verplicht!";
    } else {
        try {
            // Update de vraag in de database
            $stmt = $conn->prepare("UPDATE questions SET question = ?, answer = ?, hint = ?, roomId = ? WHERE id = ?");
            $stmt->execute([$questionText, $answer, $hint, $roomId, $id]);
            $success = "Vraag succesvol bijgewerkt!";

            // Update lokale variabele voor weergave
            $question['question'] = $questionText;
            $question['answer'] = $answer;
            $question['hint'] = $hint;
            $question['roomId'] = $roomId;
        } catch (PDOException $e) {
            $error = "Fout bij bijwerken: " . $e->getMessage();
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Vraag Bewerken</title>
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
    <h1>Vraag Bewerken</h1>

    <!-- Foutmelding tonen -->
    <?php if ($error): ?>
        <p class="error"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Succesmelding tonen -->
    <?php if ($success): ?>
        <p class="success"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Bewerk formulier -->
    <form method="post">
        <div class="form-group">
            <label for="question">Vraag:</label>
            <textarea id="question" name="question" required><?= htmlspecialchars($question['question']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="answer">Antwoord:</label>
            <input type="text" id="answer" name="answer" value="<?= htmlspecialchars($question['answer']) ?>" required>
        </div>

        <div class="form-group">
            <label for="hint">Hint (optioneel):</label>
            <textarea id="hint" name="hint"><?= htmlspecialchars($question['hint']) ?></textarea>
        </div>

        <div class="form-group">
            <label for="roomId">Room ID:</label>
            <input type="number" id="roomId" name="roomId" value="<?= (int)$question['roomId'] ?>" required>
        </div>

        <button type="submit">Bijwerken</button>
    </form>

    <p><a href="index.php">Terug naar overzicht</a></p>
</body>
</html>
