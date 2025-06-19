<?php
// Start een nieuwe sessie of hervat een bestaande
session_start();

// Inclusie van het bestand dat de databaseverbinding bevat
include 'dbcon.php';

// Foutmeldingen initialiseren voor login en registratie
$loginError = '';
$registerError = '';

// Boolean die bepaalt of het registratieformulier moet worden weergegeven
$showRegister = false;

// Controleer of het formulier via de POST-methode is verstuurd
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Als de gebruiker heeft geprobeerd in te loggen
    if (isset($_POST['login'])) {
        // Haal de ingevoerde gebruikersnaam op en verwijder witruimte
        $username = trim($_POST["username"]);
        // Haal het ingevoerde wachtwoord op
        $password = $_POST["password"];

        // Zoek de gebruiker in de database op basis van de gebruikersnaam
        $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Als gebruiker bestaat en wachtwoord klopt
        if ($user && password_verify($password, $user["password"])) {
            // Sla gebruikersgegevens op in sessie
            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];

            // Stuur gebruiker naar de hoofdpagina
            header("Location: index.php");
            exit;
        } else {
            // Login mislukt: verkeerde gebruikersnaam of wachtwoord
            $loginError = "Ongeldige gebruikersnaam of wachtwoord!";
        }
    }

    // Als de gebruiker een nieuw account probeert te registreren
    if (isset($_POST['register'])) {
        // Haal en trim de gebruikersnaam
        $username = trim($_POST["username"]);
        // Haal het wachtwoord op
        $password = $_POST["password"];
        // Toon het registratieformulier opnieuw
        $showRegister = true;

        // Controleer of gebruikersnaam en wachtwoord zijn ingevuld
        if (!empty($username) && !empty($password)) {
            // Controleer of de gebruikersnaam al bestaat
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);

            if ($stmt->rowCount() > 0) {
                // Gebruikersnaam is al in gebruik
                $registerError = "Gebruikersnaam bestaat al!";
            } else {
                // Wachtwoord versleutelen
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                // Nieuwe gebruiker toevoegen aan database
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->execute(['username' => $username, 'password' => $hashed]);

                // Terug naar loginformulier
                $showRegister = false;
                $loginError = "Registratie succesvol. Je kunt nu inloggen.";
            }
        } else {
            // Foutmelding bij lege velden
            $registerError = "Vul alle velden in.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Code Napoleon</title>
  <link rel="stylesheet" href="style.css" />
  <style>
    /* Styling voor het formuliercontainer */
    .form-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 20px;
      border: 1px solid black;
      background: rgb(0, 0, 0);
    }

    /* Verberg elementen met deze class */
    .hidden {
      display: none;
    }
  </style>
</head>
<body>

<?php if (!isset($_SESSION['user_id'])): ?>
  <!-- Alleen tonen als de gebruiker NIET is ingelogd -->
  <div class="form-container">

    <!-- Loginformulier (verborgen als registratie actief is) -->
    <div id="loginForm" <?= $showRegister ? 'class="hidden"' : '' ?>>
      <h2>Welkom bij Code Napoleon</h2>
      <p>Log in om het spel te starten:</p>

      <!-- Toon foutmelding bij login indien aanwezig -->
      <?php if (!empty($loginError)) echo "<p style='color:red;'>$loginError</p>"; ?>

      <form method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required /><br /><br />
        <input type="password" name="password" placeholder="Wachtwoord" required /><br /><br />
        <button type="submit" name="login">Login</button>
      </form>

      <!-- Link naar registratieformulier -->
      <p style="margin-top: 20px;">
        Nog geen account? <a href="#" onclick="toggleForms(); return false;">Registreer hier</a>
      </p>

      <!-- Optie voor admin login -->
      <p><a href="admin/login.php">Admin Login</a></p>
    </div>

    <!-- Registratieformulier (zichtbaar als gebruiker op 'Registreer' klikte) -->
    <div id="registerForm" <?= $showRegister ? '' : 'class="hidden"' ?>>
      <h2>Registreren</h2>

      <!-- Toon foutmelding bij registratie indien aanwezig -->
      <?php if (!empty($registerError)) echo "<p style='color:red;'>$registerError</p>"; ?>

      <form method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required /><br /><br />
        <input type="password" name="password" placeholder="Wachtwoord" required /><br /><br />
        <button type="submit" name="register">Registreer</button>
      </form>

      <!-- Link terug naar loginformulier -->
      <p style="margin-top: 20px;">
        Al een account? <a href="#" onclick="toggleForms(); return false;">Log hier in</a>
      </p>
    </div>

  </div>

  <!-- JavaScript om tussen login en registratie te wisselen -->
  <script>
    function toggleForms() {
      document.getElementById('loginForm').classList.toggle('hidden');
      document.getElementById('registerForm').classList.toggle('hidden');
    }
  </script>

<?php else: ?>
  <!-- Spelmenu: alleen zichtbaar voor ingelogde gebruikers -->
  <main>
    <h1 id="titleIndex">Code Napoleon</h1>

    <section class="buttons">
      <!-- Knop voor het verhaal -->
      <section class="button" id="storyBox" style="cursor:pointer;">
        <p>Verhaal</p>
      </section>

      <!-- Startknop voor het spel -->
      <section class="button">
        <a href="room_1.php" style="text-decoration:none; color:inherit;">
          <p id="start">Start</p>
        </a>
      </section>

      <!-- (Nog lege) knop voor scores -->
      <section class="button">
        <a href="teams.php">
        <p>Scores & teams</p>
        </a>
      </section>
    </section>

    <!-- Afbeelding -->
    <section id="imgIndex">
      <img src="./admin/img/370966_poster.jpg" alt="Napoleon" />
    </section>

    <!-- Link om uit te loggen -->
    <p style="text-align:center; margin-top:20px;"><a href="logout.php">Uitloggen</a></p>
  </main>

  <!-- Koppel externe JavaScript-bestand -->
  <script src="./app.js"></script>
<?php endif; ?>

</body>
</html>