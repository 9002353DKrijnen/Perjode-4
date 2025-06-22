<?php
session_start();
include '../dbcon.php';

// Controleer of de admin is ingelogd, zo niet redirect naar loginpagina
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
    <style>
        body { font-family: Arial, sans-serif; max-width: 1200px; margin: 0 auto; padding: 20px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 30px; }
        .menu { display: flex; gap: 20px; margin-bottom: 30px; }
        .menu a { padding: 10px 15px; background: #333; color: white; text-decoration: none; }
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        th { background-color: #f2f2f2; }
        .actions { display: flex; gap: 5px; }
        .actions a { padding: 5px 10px; text-decoration: none; }
        .edit { background: #4CAF50; color: white; }
        .delete { background: #f44336; color: white; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Admin Dashboard</h1>
        <a href="logout.php">Uitloggen</a>
    </div>

    <div class="menu">
        <a href="index.php">Vragen Overzicht</a>
        <a href="add_question.php">Vraag Toevoegen</a>
        <a href="edit_team.php">Teams bewerken</a>
    </div>

    <h2>Vragen Lijst</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Vraag</th>
                <th>Antwoord</th>
                <th>Hint</th>
                <th>Room ID</th>
                <th>Acties</th>
            </tr>
        </thead>
        <tbody>
            <?php
            // Haal alle vragen op uit de database
            $stmt = $conn->query("SELECT * FROM questions");
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "<tr>
                    <td>" . htmlspecialchars($row['id']) . "</td>
                    <td>" . htmlspecialchars($row['question']) . "</td>
                    <td>" . htmlspecialchars($row['answer']) . "</td>
                    <td>" . htmlspecialchars($row['hint']) . "</td>
                    <td>" . htmlspecialchars($row['roomId']) . "</td>
                    <td class='actions'>
                        <a href='edit_question.php?id=" . urlencode($row['id']) . "' class='edit'>Bewerk</a>
                        <a href='delete_question.php?id=" . urlencode($row['id']) . "' class='delete' onclick='return confirm(\"Weet je zeker dat je deze vraag wilt verwijderen?\")'>Verwijder</a>
                    </td>
                </tr>";
            }
            ?>
        </tbody>
    </table>
</body>
</html>
