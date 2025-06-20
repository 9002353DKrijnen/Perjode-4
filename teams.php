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
echo "<table border='1' bordercolor='white'>";
    foreach ($result as $team) {
        echo "<p>" . $team['teamnaam'] . "</p>" . "<br>";
        foreach ($team as $player => $value) {
            echo "<p>" . htmlspecialchars($value) . "</p>" . "<br>";
        }
    }
    ?>

    <!-- make teams if needed -->
    <a href="./user/add_team.php">
        <p>Maak teams</p>
    </a>
</body>

</html>