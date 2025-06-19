   <?php
// database import, and connection
include_once '../dbcon.php';

?>
<!DOCTYPE html>
<html lang="en">
    <link rel="stylesheet" href="../style.css">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team toevoegen</title>
</head>
<body>
 

<form action="" method="post">
    <label for="teamnaam">Teamnaam</label>
<input type="text" id="teamnaam" name="teamnaam" placeholder="Teamnaam">

<?php
// sql query and statement
$sqlQuery = "SELECT id, username FROM users";
$statement = $conn->prepare($sqlQuery);

// execute query
$statement->execute();

$statement->fetchAll();

?>
<input type="submit" value="Team toevoegen">

</form>










</body>
</html>