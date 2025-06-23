<?php
/* Als admin wil ik teams kunnen bekijken, aanpassen of verwijderen zodat ik het overzicht kan behouden. (Student Damiën)*/

// checkif there's an active session right now
if (PHP_SESSION_NONE == session_status()) {

    // start seession
    session_start();
}


// if the username does not have the function is Admin then it will be redirected to the home page
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] === false) {
    header("Location: ../index.php");
    exit;
}


// database import
include '../dbcon.php';

/* this page is admin-only. This means that only the admin can access this page, in order to edit,view or delete teams.
We will begin by creating an sql command. But we will first make a regular html page to get the data.  */
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <link rel="stylesheet" href="../style.css">
</head>

<body id="editTeams">
    <?php

    // sqlQuery with group_concat that takes mulitple rows from username to a single row
    $sqlQuery = "SELECT teamnaam, TeamID, GROUP_CONCAT(username ORDER BY id SEPARATOR ' - ')
     AS spelers FROM teams GROUP BY TeamID, teamnaam";


    // prepare statement
    $statement = $conn->prepare($sqlQuery);

    // run the query
    $statement->execute();

    // fetch in associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);


    // table with teams

    echo "<table id='table_editteams'>";

    // table header with basic design
    echo "<thead><tr><th>Teamnaam</th><th>Spelers</th> <th>Bewerken</th><th>Verwijderen</th></tr></thead>";

    // forach with teamname and players from database
    foreach ($result as $team) {
        echo "<tr>";
        echo "<td>{$team['teamnaam']} </td>";
        echo "<td>- {$team['spelers']}</td>";

        // edit and delete buttons
        echo "<td><a href='edit_team.php?team_id={$team['TeamID']}'>Bewerken</a></td>";
        echo "<td><a href='delete_team.php?team_id={$team['TeamID']}'>Verwijderen</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
</body>

</html>