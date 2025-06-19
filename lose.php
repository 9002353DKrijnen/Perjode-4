<!DOCTYPE html> <!-- Dit is een HTML5-pagina -->
<html lang="nl"> <!-- De taal van de pagina is Nederlands -->
<head>
    <meta charset="UTF-8"> <!-- Zorgt dat speciale tekens goed worden weergegeven -->
    <title>Game Over</title> <!-- Titel in het tabblad van de browser -->
</head>
<body style="background-color: darkred; color: white; text-align: center;">
    <!-- De stijl zorgt voor een rode achtergrond, witte tekst en gecentreerde inhoud -->

    <h1>⏳ Tijd is om!</h1> <!-- Grote koptekst met een icoon -->
    <p>Je hebt niet op tijd alle vragen beantwoord. Probeer het opnieuw.</p> <!-- Uitlegtekst -->

    <!-- Link om terug naar het begin te gaan -->
    <a href="index.php" style="color: white;">Terug naar start</a>
</body>
</html>

<?php
// Hier begint het PHP-gedeelte

session_start(); // Start of hervat de sessie

sleep(5); // Wacht 5 seconden voordat de sessie wordt verwijderd (geeft speler tijd om de pagina te zien)

session_unset(); // Verwijder alle sessie-variabelen

session_destroy(); // Verwijder de hele sessie

exit; // Stop het script
?>
