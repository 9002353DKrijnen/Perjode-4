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
        echo "<td><a href='#' class='post-link' data-action='edit_team_admin.php' data-id='{$team['TeamID']}'data-usernames= '{$team['spelers']}'>Bewerken</a></td>";
        echo "<td><a href='#' class='post-link' data-action='delete_team.php' data-id='{$team['TeamID']}'>Verwijderen</a></td>";
        echo "</tr>";
    }
    echo "</table>";
    ?>
    <!-- hidden form to make it easier to handle the post request done by the edit_team.php page -->
    <form method="post" id="postForm" style="display: none">
        <input type="hidden" name="TeamID" id="hiddenTeamID">
        <input type="hidden" name="spelers" id="hiddenUsernames">
    </form>
    <script>
        // if content is loaded load Eventlistener to the page
        document.addEventListener('DOMContentLoaded', () => {
            // import form and input
            const form = document.getElementById('postForm');
            const input = document.getElementById('hiddenTeamID');
            const usernamesOutput = document.getElementById("hiddenUsernames");

            // for each link we want to prevent to load to a random place (#), we will use preventDefault to prevent that default action and instead let javascript handle the inputted value

            document.querySelectorAll('.post-link').forEach(link => {
                link.addEventListener('click', function(event) {
                    // prevent default #
                    event.preventDefault();

                    // get the given 
                    const action = this.dataset.action;
                    const teamID = this.dataset.id;
                    const usernames = this.dataset.usernames;
                    //prevent accidental deletion 
                    if (action.includes('delete')) {
                        const isConfirmed = confirm(`Weet u zeker dat u het team met teamid ${teamID} wilt verwijderen?`)
                        if (!isConfirmed) return;
                    }
                    //input value is team id 
                    input.value = teamID;

                    // username values

                    usernamesOutput.value = usernames;
                    // delete or edit 
                    form.action = action;



                    // submit
                    form.submit();
                })
            })


        })
    </script>
</body>

</html>