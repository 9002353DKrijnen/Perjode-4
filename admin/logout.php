<?php
session_start();       // Start de sessie (om sessiegegevens te kunnen beheren)
session_destroy();     // Vernietig alle sessiegegevens (logt de gebruiker uit)
header("Location: ../index.php");  // Redirect naar de hoofdpagina
exit;                  // Stop de uitvoering van het script
?>
