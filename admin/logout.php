<?php
session_start();
session_unset();
session_destroy();

// Doorsturen naar de HOOFD-index (niet admin/index.php)
header("Location: ../index.php");
exit;
?>