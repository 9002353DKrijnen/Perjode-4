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
    <title>Napoleon's Code</title>
    <link rel="stylesheet" href="style.css">
</head>

<body data-question="<?= $questionIndex ?>">


    <?php
    switch ($questionIndex) {
        case 0: ?>
            <h1>Room 1 - Vraag <?= $questionIndex + 1 ?></h1>
            <p><?= $currentQuestion['question'] ?></p>
            <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>

            <main>
                <section class="explainer">
                    <h2>Uitleg</h2>
                    <p>Als op de afbeelding klikt komt er een geheim te voorschijn</p>
                </section>

                <section class="imgNapoleonQ1">
                    <img  src="./admin/img/FS6A9470-Chateau-de-la-Pommerie-_-Musee-Napoleon@DeclicDecolle-min-e1684331137953.jpg" alt="napoleonPuzzle">
                </section>

                <section class="hint">
                    <p>Hint</p>
                </section>
            </main>

            <form method="post">
                <input type="text" name="answer" required>
                <button type="submit">Beantwoord</button>
            </form>
        <?php
            break;
        case 1: ?>
            <h1>Room 1 - Vraag <?= $questionIndex + 1 ?></h1>
            <p><?= $currentQuestion['question'] ?></p>
            <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>
     <main class="parent">
    <div class="div1">In Frankrijk is het bij de wet verboden je varken Napoleon te noemen.
</div>
    <div class="div2">Een wijdverspreid misverstand over Napoleon Bonaparte, is dat hij een klein mannetje was. Dit beeld heeft onder andere bijgedragen aan het
begrip Napoleon-complex. In werkelijkheid was Napoleon 1,67 lang, dit was de gemiddelde lengte voor mensen uit de vroege 19e eeuw.</div>
    <div class="div3">Napoleon wilde niet door iemand anders als Keizer gekroond worden, daarom heeft hij zichzelf tot Keizer gekroond.</div>
    <div class="div4"> Code Napoleon:
Hij heeft belangrijke wetten en regels ingevoerd die bekend staan als de "Code Napoleon". </div>
    <div class="div5" >De veldslag waarbij Napoleon  definitief verslagen werd is de <p id="verhaal">Slag bij Waterloo</p>
    </div>
    <div class="div6">Jacques-Louis David:
David schilderde diverse belangrijke werken met Napoleon als onderwerp, waaronder "Napoleon steekt de Alpen over" en "De kroning van Napoleon", die nu in het Louvre in Parijs te zien zijn.</div>
    <div class="div7">Andere kunstenaars:
Er zijn tal van andere schilderijen en portretten van Napoleon, gemaakt door verschillende artiesten, zoals Jean-Auguste-Dominique Ingres, die ook portretten van de keizer en zijn familie schilderde.</div>
    <div class="div8">De 33-jarige Jean-Christophe Napoleon Bonaparte woont in Londen en is de achter-achter-achterneef van Napoleon Bonaparte I, keizer van Frankrijk van 1804 tot 1814.</div>
    <div class="div9">De Dôme des Invalides bevat het graf van Napoleon I en is een monument dat je niet mag missen tijdens het verkennen van het Parijse landschap tijdens wandeltochten door Parijs.</div>
        </main>
    



            <form method="post">
                <input type="text" name="answer" required>

                <button type="submit">Beantwoord</button>
            </form>
        <?php
            break;
        case 2: ?>
            <h1>Room 1 - Vraag <?= $questionIndex + 1 ?></h1>
            <p><?= $currentQuestion['question'] ?></p>
            <p id="timeRemaining" value="<?= $timeleft ?>"><?php echo $timeleft; ?></p>
            <form method="post">
                <input type="text" name="answer" required>
                <button type="submit">Beantwoord</button>
            </form>
    <?php
            break;
    }
    ?>

    <script src="./app.js"></script>
</body>

</html>