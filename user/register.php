<?php
include '../dbcon.php';  // Verbind met de database

// Check of het formulier via POST is verzonden
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);  // Haal en trim gebruikersnaam (verwijder spaties)
    $password = $_POST["password"];        // Haal wachtwoord op

    // Controleer of beide velden zijn ingevuld
    if (!empty($username) && !empty($password)) {
        // Kijk of de gebruikersnaam al bestaat in de database
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            // Gebruikersnaam bestaat al, geef foutmelding
            $error = "Gebruikersnaam bestaat al!";
        } else {
            // Hash het wachtwoord voor veilige opslag
            $hashed = password_hash($password, PASSWORD_DEFAULT);

            // Voeg nieuwe gebruiker toe aan de database
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $hashed]);

            // Redirect naar loginpagina na succesvolle registratie
            header("Location: login.php");
            exit;
        }
    } else {
        // Als velden niet ingevuld zijn, foutmelding tonen
        $error = "Vul alle velden in.";
    }
}
?>

<h2>Registreren</h2>

<!-- Toon foutmelding als die er is -->
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>

<!-- Registratieformulier -->
<form method="post">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <button type="submit">Registreer</button>
</form>

<p><a href="login.php">Al een account? Inloggen</a></p>
