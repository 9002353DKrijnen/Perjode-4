<?php
session_start();
include 'dbcon.php';

$loginError = '';
$registerError = '';
$showRegister = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST['login'])) {
        $username = trim($_POST["username"]);
        $password = $_POST["password"];

        $stmt = $conn->prepare("SELECT id, username, password, is_admin FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
       if ($user && password_verify($password, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["username"] = $user["username"];
    
    // geeft true of false
    $_SESSION["is_admin"] = ($user["is_admin"] == 1); 
    header("Location: index.php");
    exit;
}
        } else {
            $loginError = "Ongeldige gebruikersnaam of wachtwoord!";
        }
    }

    if (isset($_POST['register'])) {
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        $showRegister = true;

        if (!empty($username) && !empty($password)) {
            $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
            $stmt->execute(['username' => $username]);

            if ($stmt->rowCount() > 0) {
                $registerError = "Gebruikersnaam bestaat al!";
            } else {
                $hashed = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
                $stmt->execute(['username' => $username, 'password' => $hashed]);

                $showRegister = false;
                $loginError = "Registratie succesvol. Je kunt nu inloggen.";
            }
        } else {
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
    .form-container {
      max-width: 400px;
      margin: 100px auto;
      padding: 20px;
      border: 1px solid black;
      background:rgb(0, 0, 0);
    }
    .hidden {
      display: none;
    }
  </style>
</head>
<body>

<?php if (!isset($_SESSION['user_id'])): ?>
  <div class="form-container">

    <!-- Login formulier -->
    <div id="loginForm" <?= $showRegister ? 'class="hidden"' : '' ?>>
      <h2>Welkom bij Code Napoleon</h2>
      <p>Log in om het spel te starten:</p>
      <?php if (!empty($loginError)) echo "<p style='color:red;'>$loginError</p>"; ?>
      <form method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required /><br /><br />
        <input type="password" name="password" placeholder="Wachtwoord" required /><br /><br />
        <button type="submit" name="login">Login</button>
      </form>
      <p style="margin-top: 20px;">
        Nog geen account? <a href="#" onclick="toggleForms(); return false;">Registreer hier</a>
      </p>
   <?= if 
   
   
   
   
   
   
   ?>
    </div>

    <!-- Registratie formulier -->
    <div id="registerForm" <?= $showRegister ? '' : 'class="hidden"' ?>>
      <h2>Registreren</h2>
      <?php if (!empty($registerError)) echo "<p style='color:red;'>$registerError</p>"; ?>
      <form method="post">
        <input type="text" name="username" placeholder="Gebruikersnaam" required /><br /><br />
        <input type="password" name="password" placeholder="Wachtwoord" required /><br /><br />
        <button type="submit" name="register">Registreer</button>
      </form>
      <p style="margin-top: 20px;">
        Al een account? <a href="#" onclick="toggleForms(); return false;">Log hier in</a>
      </p>
    </div>

  </div>

  <script>
    function toggleForms() {
      document.getElementById('loginForm').classList.toggle('hidden');
      document.getElementById('registerForm').classList.toggle('hidden');
    }
  </script>

<?php else: ?>
  <!-- Spelmenu -->
  <main>
    <h1 id="titleIndex">Code Napoleon</h1>

    <section class="buttons">
      <section class="button" id="storyBox" style="cursor:pointer;">
        <p>Verhaal</p>
      </section>

      <section class="button">
        <a href="room_1.php" style="text-decoration:none; color:inherit;">
          <p id="start">Start</p>
        </a>
      </section>

      <section class="button">
        <p>Scores</p>
      </section>
    </section>

    <section id="imgIndex">
      <img src="./admin/img/370966_poster.jpg" alt="Napoleon" />
    </section>

    <p style="text-align:center; margin-top:20px;"><a href="logout.php">Uitloggen</a></p>
  </main>

  <script src="./app.js"></script>
<?php endif; ?>

</body>
</html>
