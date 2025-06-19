<?php
session_start();
include '../dbcon.php';

// Debug modus aanzetten, toont fouten in browser
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Als admin al is ingelogd, doorsturen naar dashboard
if (isset($_SESSION['admin_logged_in'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST["username"]);
    $password = trim($_POST["password"]);

    // Tijdelijke hardcoded admin check (handig voor debuggen)
    if ($username === 'admin' && $password === 'admin123') {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = 1; // Standaard admin ID

        // Check of de admin al in de database staat, zo niet, voeg toe
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = 'admin'");
        $stmt->execute();

        if ($stmt->rowCount() == 0) {
            $hashed = password_hash('admin123', PASSWORD_DEFAULT);
            $conn->prepare("INSERT INTO users (username, password) VALUES ('admin', ?)")->execute([$hashed]);
        }

        header("Location: index.php");
        exit;
    }

    // Check database voor gebruikersnaam en gehasht wachtwoord
    try {
        $stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user["password"])) {
            $_SESSION['admin_logged_in'] = true;
            $_SESSION['admin_id'] = $user['id'];
            header("Location: index.php");
            exit;
        } else {
            $error = "Ongeldige inloggegevens!";
        }
    } catch (PDOException $e) {
        $error = "Databasefout: " . $e->getMessage();
    }
}

// Controleer of admin account in database bestaat
$adminExists = $conn->query("SELECT 1 FROM users WHERE username = 'admin'")->rowCount() > 0;
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f5f5f5;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-box {
            background: white;
            padding: 2rem;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        h1 {
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .form-group {
            margin-bottom: 1rem;
        }
        label {
            display: block;
            margin-bottom: 0.5rem;
            color: #555;
        }
        input {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
            font-size: 1rem;
        }
        button {
            width: 100%;
            padding: 0.8rem;
            background-color: #2c3e50;
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 1rem;
            cursor: pointer;
            margin-top: 1rem;
        }
        button:hover {
            background-color: #1a252f;
        }
        .error {
            color: #e74c3c;
            text-align: center;
            margin: 1rem 0;
        }
        .debug-info {
            background: #f8f9fa;
            padding: 1rem;
            margin-top: 1rem;
            border-radius: 4px;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="login-box">
        <h1>Admin Login</h1>
        
        <?php if ($error): ?>
            <div class="error"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        
        <form method="post">
            <div class="form-group">
                <label for="username">Gebruikersnaam:</label>
                <input type="text" id="username" name="username" required value="admin">
            </div>
            
            <div class="form-group">
                <label for="password">Wachtwoord:</label>
                <input type="password" id="password" name="password" required value="admin123">
            </div>
            
            <button type="submit">Inloggen</button>
        </form>
        
        <?php if (!$adminExists): ?>
            <div class="debug-info">
                <p><strong>Let op:</strong> Admin account bestaat niet in database.</p>
                <p>Er wordt automatisch een admin account aangemaakt bij eerste login.</p>
                <p>Gebruik: <strong>admin</strong> / <strong>admin123</strong></p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>
