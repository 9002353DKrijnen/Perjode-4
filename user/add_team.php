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


// You want to have the result fetched as an array, so we can use foreach to have a new line in the table for each record 
$result = $statement->fetchAll();

// foreach loop for result (input)
echo "<select id='username'>";
foreach ($result as $user) {
    // instead of string concatenation we use string interpolation, because I find it much easier to read. 
echo "<option value='{$user['id']}'>{$user['username']} </option>";
}

?>
<input type="submit" value="Team toevoegen">

</form>









</body>
</html>