   <?php
    // database import, and connection
    include_once '../dbcon.php';



    ?>
   <!DOCTYPE html>
   <html lang="en">

   <head>
       <meta charset="UTF-8">
       <meta name="viewport" content="width=device-width, initial-scale=1.0">
       <title>Team toevoegen</title>
       <link rel="stylesheet" href="../style.css">

   </head>

   <body>


       <form action="" method="post" class="form">
           <label for="teamnaam">Teamnaam</label>
           <input type="text" id="teamnaam" name="teamnaam" placeholder="Teamnaam" class="button">

           <?php
            // sql query and statement
            $sqlQuery = "SELECT id, username FROM users";
            $statement = $conn->prepare($sqlQuery);

            // execute query
            $statement->execute();


            // You want to have the result fetched as an array, so we can use foreach to have a new line in the table for each record 
            $result = $statement->fetchAll();

            // foreach loop for result (input)
            echo "<select name='user_1' class='button'>";
            foreach ($result as $user) {
                // instead of string concatenation we use string interpolation, because I find it much easier to read. 
                echo "<option value='{$user['username']}'>{$user['username']} </option>";
            }
            echo "</select>";

            // per team 2 users
            echo "<select name='user_2' class='button'>";
            foreach ($result as $user) {
                // instead of string concatenation we use string interpolation, because I find it much easier to read. 
                echo "<option value='{$user['username']}'>{$user['username']} </option>";
            }
            echo "</select>";

            ?>

           <input type="submit" value="Team toevoegen" class="button">

       </form>

       <style>
           .form {
               display: flex;
               flex-direction: column;
               justify-content: center;
               align-items: center;
               height: 100vh;
           }

           .form>* {
               margin: 10px;
           }

           /*  show the error above all. It is fixed so it is easy readable and hard to miss */
           #error {
               display: block;
               position: fixed;
               width: 100%;
               text-align: center;
               height: 20%;
               top: 0;
               left: 0;

            p{
                color: white;
            }
           }
       </style>



       <?php
        // if form has been submitted make both users able to converted into easy to read variables
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $teamname = $_POST['teamnaam'];
            $user1 = $_POST['user_1'];
            $user2 = $_POST['user_2'];


            // easy validation using switch
            switch (true) {
                case empty($teamname):

                    // if teamname is empty, show error
                    $error = "Teamnaam mag niet leeg zijn";
                    break;
                // if user 1 is equal to user 2, show error
                case $user1 === $user2:
                    $error = "Gebruikers mogen niet  hetzelfde zijn.";
                    break;
                default:
                    // null if there's no error at all. 
                    $error = null;
                    break;
            }
        }

        // if there's an error, we will show it here.
        if ($error) {
            echo "<p id='error' style='color:red;'>$error</p>";
            return;
        }


        // declare user_1 and user_2 as an array

        $user = [$user1, $user2];

        //Gain latest team ID and add + 1
        $sqlQuery = "SELECT MAX(TeamID) as teamID FROM teams";
        $statement = $conn->prepare($sqlQuery);
        $statement->execute();
        $teamID = $statement->fetch(PDO::FETCH_ASSOC);
        $teamID = $teamID['teamID'] + 1;


        // make statement and insert
        for ($i = 0; $i < 2; $i++) {
            $sqlQuery = "INSERT INTO teams (TeamID, teamnaam, username) VALUES (:teamID, :teamnaam, :username)";
            $statement = $conn->prepare($sqlQuery);
            $statement->execute([
                ':teamID' => $teamID,
                ':teamnaam' => $teamname,
                ':username' => $user[$i]
            ]);
        }
        if($statement){
            // succes rederict 
            header("Location: ../teams.php");
            exit;
        }
        ?>



   </body>

   </html>