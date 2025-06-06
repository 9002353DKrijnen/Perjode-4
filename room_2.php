<?php
session_start();
include 'dbcon.php';

// ✅ Timer controleren, maar nooit opnieuw starten
if (!isset($_SESSION['start_time']) || !isset($_SESSION['duration'])) {
    header("Location: lose.php");
    exit;
}

$endTime = $_SESSION['start_time'] + $_SESSION['duration'];
$timeleft = $endTime - time();

if (time() > $endTime) {
    header("Location: lose.php");
    exit;
}

// Kamer 2 vragen ophalen
$roomId = 2;
$questionIndex = $_SESSION['questionIndex2'] ?? 0;

$stmt = $conn->prepare("SELECT * FROM questions WHERE roomId = :roomId ORDER BY id ASC");
$stmt->execute(['roomId' => $roomId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (count($questions) === 0) {
    echo "Geen vragen gevonden voor deze kamer.";
    exit;
}

$currentQuestion = $questions[$questionIndex];
$showHint = false;
$showAnswerForm = true;

// Antwoord verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userAnswer = trim(strtolower($_POST['answer']));

    if ($questionIndex == 2) {
        $validAnswers = ['slag bij austerlitz', 'austerlitz'];

        if (in_array($userAnswer, $validAnswers)) {
            $questionIndex++;
            $_SESSION['questionIndex2'] = $questionIndex;

            if ($questionIndex >= count($questions)) {
                $_SESSION['questionIndex2'] = 0;
                header("Location: win.php");
                exit;
            } else {
                header("Location: room_2.php");
                exit;
            }
        } else {
            $showHint = true;
        }

    } else {
        $correctAnswer = strtolower($currentQuestion['answer']);

        if ($userAnswer === $correctAnswer) {
            $questionIndex++;
            $_SESSION['questionIndex2'] = $questionIndex;

            if ($questionIndex >= count($questions)) {
                $_SESSION['questionIndex2'] = 0;
                header("Location: win.php");
                exit;
            } else {
                header("Location: room_2.php");
                exit;
            }
        } else {
            $showHint = true;
        }
    }
}

$showStory = false;
if ($questionIndex == 1) {
    if (isset($_GET['showStory']) && $_GET['showStory'] == '1') {
        $showStory = true;
    } else {
        $showAnswerForm = false;
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Escape Room 2</title>
    <style>
        body {
            background-color: black;
            color: white;
            text-align: center;
            font-family: Arial, sans-serif;
        }
        img.clickable {
            cursor: pointer;
            max-width: 400px;
            border: 2px solid white;
            border-radius: 8px;
            margin: 20px auto;
            display: block;
        }
        .story-text {
            margin: 20px auto;
            max-width: 600px;
            background: #222;
            padding: 15px;
            border-radius: 10px;
            font-size: 18px;
            line-height: 1.4;
        }
        input[type=text] {
            padding: 8px;
            font-size: 16px;
            width: 300px;
            border-radius: 5px;
            border: none;
            margin-top: 15px;
        }
        button {
            margin-top: 15px;
            padding: 10px 25px;
            font-size: 16px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            background-color: #4CAF50;
            color: white;
        }
        button:hover {
            background-color: #45a049;
        }
        a.back-link {
            color: lightblue;
            display: inline-block;
            margin-top: 30px;
            text-decoration: none;
            font-size: 16px;
        }
        a.back-link:hover {
            text-decoration: underline;
        }
        p.hint {
            color: red;
            margin-top: 20px;
            font-weight: bold;
        }
        .verhalen-container {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            max-width: 1000px;
            margin: auto;
        }
        .verhaal-box {
            background-color: #1c1c1c;
            border: 1px solid white;
            padding: 12px;
            width: 230px;
            height: 200px;
            overflow-y: auto;
            border-radius: 8px;
            font-size: 14px;
            text-align: left;
        }
    </style>
</head>
<body>

<h1>Room 2 - Vraag <?= $questionIndex + 1 ?></h1>
<p><?= htmlspecialchars($currentQuestion['question']) ?></p>

<!-- ✅ Timer weergeven -->
<p id="timeRemaining"><?php echo $timeleft; ?></p>

<?php if ($questionIndex == 0): ?>
    <form method="post">
        <div style="display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; max-width: 900px; margin: auto;">
            <?php
            $verhalen = [
                "In het oude Frankrijk heerste chaos in het rechtssysteem. Keizer Napoleon gaf de opdracht om alles te ordenen in één duidelijke wetbundel. Die bundel werd bekend als de ____. Het bracht orde in de maatschappij.",
                "Na de revolutie ontstond er verwarring over wetten. Een commissie bedacht toen de Burgercode, gebaseerd op Romeins recht. Toch werd die nooit officieel ingevoerd.",
                "In Duitsland ontwikkelde men de Grundgesetz, maar dat gebeurde jaren na Napoleon. Het werd de grondwet van de Bondsrepubliek.",
                "Napoleon wilde wetten voor iedereen. Hij stelde juristen aan die een overzicht maakten, maar noemden het toen ‘Wettencollectie’. Later werd dat afgekeurd.",
                "Sommige landen volgden het Engelse model van common law. Die had geen geschreven code zoals in Frankrijk.",
                "In Spanje werd gedacht aan de 'Código Civil'. Dat was vergelijkbaar met wat Napoleon wilde, maar het had een andere naam en structuur."
            ];
            foreach ($verhalen as $tekst) {
                echo "<div style='background-color: #1c1c1c; border: 1px solid white; padding: 10px; width: 250px; height: 180px; overflow-y: auto; border-radius: 8px; font-size: 14px;'>$tekst</div>";
            }
            ?>
        </div>

        <br>
        <p>Typ hieronder het juiste antwoord (één woord of korte naam):</p>
        <input type="text" name="answer" required autocomplete="off" style="padding: 8px; width: 300px;">
        <br><br>
        <button type="submit">Beantwoord</button>
    </form>

<?php elseif ($questionIndex == 1): ?>
    <?php if (!$showStory): ?>
        <a href="?showStory=1">
            <img src="admin/img/vraag2.png" alt="Vraag 2 afbeelding" class="clickable">
        </a>
        <p>Klik op de afbeelding om het verhaal te zien en de vraag te beantwoorden.</p>

    <?php else: ?>
        <div class="story-text">
            <p>
                Na zijn nederlaag en gedwongen ballingschap stond Napoleon voor een grote uitdaging: terugkeren naar het politieke toneel en zijn macht herwinnen. Zijn eerste ballingschap vond plaats op een klein eiland in de Middellandse Zee. Dit eiland was speciaal gekozen omdat het ver weg en moeilijk bereikbaar was.

                Het eiland deelt zijn naam met een beroemde wijnstreek in Frankrijk, bekend om zijn sterke, rode wijnen. Napoleon liet zich niet zomaar gevangen houden en vond een manier om te ontsnappen. Dit moment markeert het begin van zijn terugkeer naar de macht.
            </p>
        </div>

        <?php if ($showAnswerForm): ?>
        <form method="post">
            <input type="text" name="answer" required autocomplete="off" placeholder="Typ je antwoord hier">
            <br>
            <button type="submit">Beantwoord</button>
        </form>
        <?php endif; ?>
    <?php endif; ?>

<?php elseif ($questionIndex == 2): ?>
    <form method="post">
        <div class="verhalen-container">
            <?php
            $verhalenVraag3 = [
                "Tijdens de campagne van Rusland ondervond Napoleon de wreedheid van de winter. Zijn troepen leden onder extreme kou en gebrek aan voedsel. De slag om Moskou bleek geen echte overwinning, ondanks de inname van de stad.",
                "De veldslag bij Leipzig, ook wel de 'Volkerenslag' genoemd, bracht Napoleon een grote nederlaag. Veel Europese mogendheden verenigden zich en dwongen zijn terugtocht naar Frankrijk.",
                "In de buurt van het huidige Tsjechië vond in 1805 een veldslag plaats waarbij Napoleon een grote overwinning boekte. Zijn strategie om de vijand te misleiden werkte perfect. Drie keizers waren betrokken bij deze slag, waardoor het bekend werd als de 'Driekeizerslag'.",
                "De Slag bij Trafalgar was op zee, waar de Britse vloot onder leiding van admiraal Nelson Napoleon versloeg. Hierdoor verloor Frankrijk haar hoop op een invasie van Engeland."
            ];
            foreach ($verhalenVraag3 as $tekst) {
                echo "<div class='verhaal-box'>$tekst</div>";
            }
            ?>
        </div>

        <br>
        <p>Typ hieronder de naam van deze veldslag:</p>
        <input type="text" name="answer" required autocomplete="off" placeholder="Typ hier je antwoord">
        <br><br>
        <button type="submit">Beantwoord</button>
    </form>

<?php else: ?>
    <form method="post">
        <input type="text" name="answer" required autocomplete="off" placeholder="Typ je antwoord hier">
        <button type="submit">Beantwoord</button>
    </form>
<?php endif; ?>

<?php if ($showHint): ?>
    <p class="hint">Dat is niet correct. Hint: <?= htmlspecialchars($currentQuestion['hint']) ?></p>
<?php endif; ?>

<a href="index.php" class="back-link">Terug naar start</a>

<!-- ✅ JavaScript timer -->
<script src="./app.js"></script>

</body>
</html>
