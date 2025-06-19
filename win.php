<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Je bent ontsnapt!</title> <!-- Titel van de pagina die in het tabblad wordt getoond -->
</head>
<body style="background-color: green; color: white; text-align: center;">
    <!-- Stijl: groene achtergrond, witte tekst en gecentreerde tekst -->

    <h1>🎉 Gefeliciteerd!</h1> <!-- Grote kop met een feestemoji -->
    <p>Je hebt alle vragen goed beantwoord en bent op tijd ontsnapt!</p> <!-- Bericht voor de gebruiker -->

    <!-- Link om terug te gaan naar de startpagina -->
    <a href="index.php" style="color: white;">Terug naar home</a>
</body>
</html>

<?php
// PHP code die sessiegegevens opruimt zodra deze pagina geladen wordt

session_start();    // Start de sessie, zodat we sessievariabelen kunnen aanpassen
session_unset();    // Verwijdert alle sessievariabelen (zoals login, voortgang, timer)
session_destroy();  // Vernietigt de sessie helemaal

exit; // Stop de uitvoering van dit script hier
?>
