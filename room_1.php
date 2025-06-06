<?php
session_start();
include 'dbcon.php';
// let a timer play for 500 seconds
// session start 


// if there's not an active session at this time there will be one made.

if (!isset($_SESSION['start_time'])) {

    // create a start time to compare to.
    $_SESSION['start_time'] = time();

    // set a duration in seconds
    $_SESSION['duration'] = 300;
}

// endtime is being calculated by adding the duration to the start time. If it expires the user will be sent to lose.php

// current time in $currentTime with the functions time()

$endTime = $_SESSION['start_time'] + $_SESSION['duration'];


// write timer to screen
$timeleft = $endTime - time();
// the comparision itself is done here.

if (time() > $endTime) {
    header("Location: lose.php");
    exit;
}


// room id
$roomId = 1;
// question index
$questionIndex = $_SESSION['questionIndex'] ?? 0;

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
        $_SESSION['questionIndex'] = $questionIndex;

        if ($questionIndex >= count($questions)) {
            $_SESSION['questionIndex'] = 0;
            header("Location: room_2.php");
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
    <title>Code Napoleon</title>
</head>

<body style="background-color: black; color: white; text-align: center;" data-question="<?= $questionIndex + 1 ?>">
<?php
// Voorzichtige switch voor verschillende HTML per vraag
switch ($questionIndex) {
    case 0:
        ?>
        <h1>Vraag 1</h1>
        <p><?= htmlspecialchars($currentQuestion['question']) ?></p>
        <form method="post">
            <input type="text" name="answer" required>
            <button type="submit">Beantwoord</button>
                <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>

        </form>
        <?php
        break;

    case 1:
        ?>
        <h2>Vraag 2 - iets andere layout</h2>
        <div class="question-wrapper">
            <p><strong><?= htmlspecialchars($currentQuestion['question']) ?></strong></p>
            <form method="post">
                <textarea name="answer" required></textarea>
                <button type="submit">Antwoord verzenden</button>
            </form>
                <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>

        </div>
        <?php
        break;

    case 2:
        ?>
        <section class="last-question">
            <h3>Laatste vraag</h3>
            <p><?= htmlspecialchars($currentQuestion['question']) ?></p>
            <form method="post">
                <input type="text" name="answer" required autocomplete="off">
                <button type="submit">Check</button>
            </form>
                <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>

        </section>
        <?php
        break;

    default:
        ?>
        <p>Onbekende vraag.</p>
        <?php
        break;
}
?>
