<?php
/* Als admin wil ik teams kunnen bekijken, aanpassen of verwijderen zodat ik het overzicht kan behouden. (Student Damiën)*/

// checkif there's an active session right now
if(PHP_SESSION_NONE == session_status()) {

    // start seession
    session_start();
}


// if the username does not have the function is Admin then it will be redirected to the home page
if(!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] === false) {
    header("Location: ../index.php");
    exit;
}


// database import
include '../dbcon.php';



?>