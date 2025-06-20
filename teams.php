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

    // make sql query and statement
    $sqlQuery = "SELECT * FROM teams";
    $statement = $conn->prepare($sqlQuery);

    // execute query
    $statement->execute();

    // fetch results
    $result = $statement->fetchAll(PDO::FETCH_ASSOC);
    echo "<table border='1' callpadding='10' cellspacing='0' id='table'>";
    echo "<tr><th>teamnaam</th><th>Speler 1</th><th>Speler 2</th></tr>";
        foreach($result as $team) {
            echo "<tr> ";
            echo "<td>" . $team['teamnaam'] . "</td>";
            foreach($team as $key => $value) {
               echo "<td>" . $team['username'] . "</td>";

            }
            echo "</tr>";
            
        }



    ?>
    <style>
        #table{
    width: 100%;
    border-collapse: collapse;
    margin: 0 auto;
    border: 1px solid #000;
     *{
        color: white;
     }
}

        </style>
    <a href="./user/add_team.php">
        <p>Maak teams</p>
    </a>
</body>

</html>