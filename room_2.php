<?php
session_start();
include 'dbcon.php';



// endtime is being calculated by adding the duration to the start time. If it expires the user will be sent to lose.php

// current time in $currentTime with the functions time()

$endTime = $_SESSION['start_time'] + $_SESSION['duration'];


// write timer to screen
$timeleft = $endTime - time();
// the comparision itself is done here.

if(time() > $endTime) {
    header("Location: lose.php");
    exit;
}

$roomId = 2;
$questionIndex = $_SESSION['questionIndex2'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM questions WHERE roomId = :roomId");
$stmt->execute(['roomId' => $roomId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($questions) === 0) {
    echo "Geen vragen gevonden voor deze kamer.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswer = trim(strtolower($_POST['answer']));
    $correctAnswer = strtolower($questions[$questionIndex]['answer']);

    if ($userAnswer === $correctAnswer) {
        $questionIndex++;
        $_SESSION['questionIndex2'] = $questionIndex;

        if ($questionIndex >= count($questions)) {
            $_SESSION['questionIndex2'] = 0;
            header("Location: win.php");
            exit;
        }
    } else {
        $hint = $questions[$questionIndex]['hint'];
        echo "<p style='color:red;'>Fout antwoord. Hint: $hint</p>";
    }
}

$currentQuestion = $questions[$questionIndex];
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Escape Room 2</title>
</head>
<body style="background-color: black; color: white; text-align: center;">
    <h1>Room 2 - Vraag <?= $questionIndex + 1 ?></h1>
    <p><?= $currentQuestion['question'] ?></p>
        <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>


    <form method="post">
        <input type="text" name="answer" required>
        <button type="submit">Beantwoord</button>
    </form>

   

    <script src="./app.js"></script>
</body>
</html>
