<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Teams</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <?php
    // sessions start if it doesn't already exist
    if (PHP_SESSION_NONE === session_status()) {
        session_start();
    }
    // get database connection
    include 'dbcon.php';
    // sqlQuery
    $sqlQuery = "
    SELECT teamnaam, GROUP_CONCAT(username ORDER BY id SEPARATOR ' - ') AS spelers FROM teams GROUP BY TeamID, teamnaam";
    // prepare statement
    $statement = $conn->prepare($sqlQuery);

    // run the query
    $statement->execute();

    // fetch in associative array
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);


    // generate a table
    echo "<table border='1' cellpadding='10' cellspacing='0' id='table'>";

    // table headers
    echo "<tr><th>teamnaam</th><th>Speler 1 - Speler 2</th></tr>";

    // foreach team generate a row with players
    foreach ($result as $team) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($team['teamnaam']) . "</td>";
        echo "<td>" . htmlspecialchars($team['spelers']) . "</td>";
        echo "</tr>";
    }

    // end table
    echo "</table>";



    ?>
    <!-- button to make teams -->
    <a href="./user/add_team.php">
        <p>Maak teams</p>
    </a>
</body>

</html>