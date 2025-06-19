<?php
session_start();
require '../dbcon.php';

// Alleen toegang voor admin
if (!isset($_SESSION['user_id']) || $_SESSION['username'] !== 'admin') {
    header("Location: ../index.php");
    exit;
}

$questions = [];
$error = '';

// Verwijder vraag als delete parameter bestaat
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    try {
        $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
        $stmt->execute([$id]);
        header("Location: show_all_questions.php?success=Vraag+verwijderd");
        exit;
    } catch (PDOException $e) {
        $error = "Verwijderen mislukt: " . $e->getMessage();
    }
}

// Haal alle vragen op
try {
    $stmt = $conn->query("SELECT * FROM questions ORDER BY roomId, id");
    $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    $error = "Fout bij ophalen vragen: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Beheer Vragen</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        h1 { color: #333; }
        .success { color: green; padding: 10px; background: #e6ffe6; }
        .error { color: red; padding: 10px; background: #ffebeb; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .actions a { margin-right: 10px; text-decoration: none; padding: 5px 10px; }
        .edit { background: #4CAF50; color: white; }
        .delete { background: #f44336; color: white; }
        .add { background: #2196F3; color: white; padding: 10px; display: inline-block; margin-bottom: 20px; }
    </style>
</head>
<body>
    <h1>Beheer Vragen</h1>
    
    <?php if (isset($_GET['success'])): ?>
        <div class="success"><?= htmlspecialchars(urldecode($_GET['success'])) ?></div>
    <?php endif; ?>
    
    <?php if ($error): ?>
        <div class="error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    
    <a href="add_question.php" class="add">Nieuwe Vraag Toevoegen</a>
    
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vraag</th>
                <th>Antwoord</th>
                <th>Hint</th>
                <th>Kamer ID</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($questions as $question): ?>
                <tr>
                    <td><?= htmlspecialchars($question['id']) ?></td>
                    <td><?= htmlspecialchars($question['question']) ?></td>
                    <td><?= htmlspecialchars($question['answer']) ?></td>
                    <td><?= htmlspecialchars($question['hint'] ?? '-') ?></td>
                    <td><?= htmlspecialchars($question['roomId']) ?></td>
                    <td class="actions">
                        <a href="edit_question.php?id=<?= $question['id'] ?>" class="edit">Bewerken</a>
                        <a href="show_all_questions.php?delete=<?= $question['id'] ?>" class="delete" 
                           onclick="return confirm('Weet u zeker dat u deze vraag wilt verwijderen?')">Verwijderen</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <p style="margin-top: 20px;">
        <a href="../index.php">Terug naar hoofdpagina</a>
    </p>
</body>
</html>