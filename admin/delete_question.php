<?php
session_start();
include '../dbcon.php';

// Check of admin is ingelogd, anders naar loginpagina
if (!isset($_SESSION['admin_logged_in'])) {
    header("Location: login.php");
    exit;
}

// Check of er een id is meegegeven via GET, anders terug naar overzicht
if (!isset($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = (int)$_GET['id'];  // Zorg dat id een integer is ter veiligheid

try {
    // Bereid DELETE query voor om vraag met deze id te verwijderen
    $stmt = $conn->prepare("DELETE FROM questions WHERE id = ?");
    $stmt->execute([$id]);
} catch (PDOException $e) {
    // Hier kun je eventueel de fout loggen of afhandelen
}

// Na verwijderen terug naar overzicht
header("Location: index.php");
exit;
?>
