<?php
session_start();
include '../dbcon.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["username"] = $user["username"];
        header("Location: ../index.php");  // redirect naar startpagina
        exit;
    } else {
        $error = "Ongeldige gebruikersnaam of wachtwoord!";
    }
}
?>

<h2>Inloggen</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <button type="submit">Login</button>
</form>
<p><a href="register.php">Nog geen account? Registreer hier</a></p>