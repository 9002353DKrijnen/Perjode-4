<?php
include '../dbcon.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    if (!empty($username) && !empty($password)) {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = :username");
        $stmt->execute(['username' => $username]);

        if ($stmt->rowCount() > 0) {
            $error = "Gebruikersnaam bestaat al!";
        } else {
            $hashed = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (:username, :password)");
            $stmt->execute(['username' => $username, 'password' => $hashed]);
            header("Location: login.php");
            exit;
        }
    } else {
        $error = "Vul alle velden in.";
    }
}
?>

<h2>Registreren</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="post">
    Gebruikersnaam: <input type="text" name="username" required><br>
    Wachtwoord: <input type="password" name="password" required><br>
    <button type="submit">Registreer</button>
</form>
<p><a href="login.php">Al een account? Inloggen</a></p>