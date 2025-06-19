<?php
session_start();  // Start een sessie om gebruikersgegevens bij te houden

include '../dbcon.php';  // Verbinding maken met de database (zorg dat dit bestand bestaat en juist is)

// Check of het formulier via POST is verzonden
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Haal gebruikersnaam en wachtwoord uit het formulier, gebruikersnaam trimmen om spaties te verwijderen
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    // Bereid een SQL-statement voor om de gebruiker op te zoeken op basis van de gebruikersnaam
    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);  // Voer de query uit met de opgegeven gebruikersnaam
    $user = $stmt->fetch(PDO::FETCH_ASSOC);  // Haal het resultaat op als associatieve array

    // Controleer of gebruiker bestaat én het wachtwoord klopt (password_verify vergelijkt gehashte wachtwoorden)
    if ($user && password_verify($password, $user["password"])) {
        // Sla gebruikersgegevens op in de sessie
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        
        // Redirect naar de startpagina (index.php)
        header("Location: ../index.php");
        exit;  // Stop verdere uitvoering
    } else {
        // Als inloggen mislukt, toon een foutmelding
        $error = "Ongeldige gebruikersnaam of wachtwoord!";
    }
}
?>

<h2>Inloggen</h2>

<!-- Als er een foutmelding is, laat deze dan zien -->
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<!-- Loginformulier -->
<form method="post">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>

<p><a href="register.php">Nog geen account? Registreer hier</a></p>
