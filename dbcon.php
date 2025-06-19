<?php
// Databaseconfiguratie
$host = 'localhost';            // De hostnaam van de database (meestal 'localhost')
$dbname = 'escape-room';        // De naam van de database
$username = 'root';             // De gebruikersnaam om in te loggen op de database
$password = '';                 // Het wachtwoord (leeg bij standaard XAMPP/WAMP/MAMP)

try {
    // Maak een nieuwe PDO-verbinding met de opgegeven instellingen
    $conn = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);

    // Stel de foutmodus in op ERRMODE_EXCEPTION om fouten als exceptions te behandelen
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    // Als er iets misgaat met verbinden, toon een foutmelding en stop het script
    die("Databaseverbinding mislukt: " . $e->getMessage());
}

// Hulpfunctie om te controleren of een gebruiker adminrechten heeft
function isAdmin() {
    // Retourneer true als de sessievariabele 'is_admin' bestaat én gelijk is aan 1
    return isset($_SESSION['is_admin']) && $_SESSION['is_admin'] == 1;
}
?>
