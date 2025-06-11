<?php
session_start();
include 'dbcon.php';

// Timer instellen
if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();
    $_SESSION['duration'] = 300; // 5 minuten (300 seconden)
}

// Controleer eerst of de tijd al verstreken is
if (isset($_SESSION['start_time']) && (time() - $_SESSION['start_time'] > $_SESSION['duration'])) {
    header("Location: lose.php");
    exit();
}

// Bereken resterende tijd
$timeleft = ($_SESSION['start_time'] + $_SESSION['duration']) - time();
$minutes = floor($timeleft / 60);
$seconds = $timeleft % 60;

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

// Antwoord verwerken
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controleer of de tijd niet verstreken is
    if (time() - $_SESSION['start_time'] > $_SESSION['duration']) {
        header("Location: lose.php");
        exit();
    }

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
            }
        } else {
            $showHint = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="vragen.css">
    <title>Napoleon's Geheimen - Kamer 2</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏛️ Napoleon's Geheimen - Kamer 2</h1>
            <div class="timer" id="timeRemaining" value="<?= $timeleft ?>">
                <?php echo $minutes . " minuten en " . $seconds . " seconden"; ?>
            </div>
        </div>

        <div class="question-container">
            <h2><?= htmlspecialchars($currentQuestion['question']) ?></h2>

            <?php if ($questionIndex == 0): ?>
                <!-- Vraag 1: Code Napoleon Interactieve Puzzle -->
                <div class="instruction">
                    <p><strong>🔍 Zoek de juiste geschiedenis!</strong> Klik op de verhalen om ze te ontdekken. Eén verhaal bevat de sleutel tot het antwoord...</p>
                </div>
                
                <div class="code-puzzle">
                    <?php
                    $verhalen = [
                        "correct" => "In het oude Frankrijk heerste chaos in het rechtssysteem. Keizer Napoleon gaf de opdracht om alles te ordenen in één duidelijke wetbundel. Die bundel werd bekend als de <strong>Code Napoleon</strong>. Het bracht orde in de maatschappij en werd model voor veel andere landen.",
                        "fake1" => "Na de revolutie ontstond er verwarring over wetten. Een commissie bedacht toen de Burgercode, gebaseerd op Romeins recht. Deze werd echter nooit officieel ingevoerd door Napoleon.",
                        "fake2" => "In Duitsland ontwikkelde men de Grundgesetz, maar dat gebeurde jaren na Napoleon. Het werd de grondwet van de Bondsrepubliek en had weinig met Napoleon te maken.",
                        "fake3" => "Napoleon wilde wetten voor iedereen. Hij stelde juristen aan die een overzicht maakten, maar noemden het toen 'Wettencollectie'. Later werd dit afgekeurd door de senaat.",
                        "fake4" => "Sommige landen volgden het Engelse model van common law. Dit systeem had geen geschreven code zoals Napoleon wilde introduceren in Frankrijk.",
                        "fake5" => "In Spanje werd gedacht aan de 'Código Civil'. Dat was vergelijkbaar met wat Napoleon wilde, maar het had een andere naam en werd pas veel later ontwikkeld."
                    ];
                    
                    foreach ($verhalen as $key => $tekst) {
                        echo "<div class='story-card' data-story='$key' onclick='revealStory(this)'>
                                <div class='story-content' style='display:none;'>$tekst</div>
                                <div class='story-preview'>📜 Klik om te ontdekken</div>
                              </div>";
                    }
                    ?>
                </div>

                <form method="post" class="answer-form">
                    <p><strong>Wat is de naam van Napoleon's beroemde wetboek?</strong></p>
                    <input type="text" name="answer" class="answer-input" placeholder="Typ hier je antwoord..." required>
                    <br>
                    <button type="submit" class="submit-btn">Bevestig Antwoord</button>
                </form>

            <?php elseif ($questionIndex == 1): ?>
                <!-- Vraag 2: Elba Mystery -->
                <div class="mystery-container">
                    <div class="instruction">
                        <p><strong>🏝️ Het Mysterie van de Ballingschap</strong> Klik op de geheimzinnige kaart om het verhaal van Napoleon's ontsnapping te onthullen...</p>
                    </div>
                    
                    <div class="mystery-image">
                        <img src="admin/img/vraag2.png" alt="Mysteriekaart" onclick="revealMystery()">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 24px; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); pointer-events: none;">
                            🗺️ KLIK HIER
                        </div>
                    </div>

                    <div class="story-reveal" id="mysteryStory">
                        <h3>🏝️ Het Geheim van het Eiland</h3>
                        <p style="font-size: 18px; line-height: 1.6;">
                            Na zijn nederlaag en gedwongen ballingschap stond Napoleon voor een grote uitdaging: terugkeren naar het politieke toneel en zijn macht herwinnen. Zijn eerste ballingschap vond plaats op een klein eiland in de Middellandse Zee. Dit eiland was speciaal gekozen omdat het ver weg en moeilijk bereikbaar was.
                        </p>
                        <p style="font-size: 18px; line-height: 1.6; margin-top: 15px;">
                            <strong>Het eiland deelt zijn naam met een beroemde wijnstreek in Frankrijk, bekend om zijn sterke, rode wijnen.</strong> Napoleon liet zich niet zomaar gevangen houden en vond een manier om te ontsnappen. Dit moment markeert het begin van zijn terugkeer naar de macht.
                        </p>
                        
                        <form method="post" class="answer-form" style="margin-top: 30px;">
                            <p><strong>Op welk eiland werd Napoleon in ballingschap gestuurd?</strong></p>
                            <input type="text" name="answer" class="answer-input" placeholder="Naam van het eiland..." required>
                            <br>
                            <button type="submit" class="submit-btn">Bevestig Antwoord</button>
                        </form>
                    </div>
                </div>

            <?php elseif ($questionIndex == 2): ?>
                <!-- Vraag 3: Battle Interactive -->
                <div class="instruction">
                    <p><strong>⚔️ De Grote Veldslagen</strong> Lees de verhalen van Napoleon's veldslagen. Eén van deze beschrijft zijn grootste overwinning - de "Driekeizerslag"!</p>
                </div>
                
                <div class="battle-stories">
                    <?php
                    $veldslagen = [
                        "rusland" => "🥶 Tijdens de campagne van Rusland ondervond Napoleon de wreedheid van de winter. Zijn troepen leden onder extreme kou en gebrek aan voedsel. De slag om Moskou bleek geen echte overwinning, ondanks de inname van de stad.",
                        "leipzig" => "🌍 De veldslag bij Leipzig, ook wel de 'Volkerenslag' genoemd, bracht Napoleon een grote nederlaag. Veel Europese mogendheden verenigden zich en dwongen zijn terugtocht naar Frankrijk.",
                        "austerlitz" => "👑 In de buurt van het huidige Tsjechië vond in 1805 een veldslag plaats waarbij Napoleon een grote overwinning boekte. Zijn strategie om de vijand te misleiden werkte perfect. Drie keizers waren betrokken bij deze slag, waardoor het bekend werd als de 'Driekeizerslag'.",
                        "trafalgar" => "🚢 De Slag bij Trafalgar was op zee, waar de Britse vloot onder leiding van admiraal Nelson Napoleon versloeg. Hierdoor verloor Frankrijk haar hoop op een invasie van Engeland."
                    ];
                    
                    foreach ($veldslagen as $key => $tekst) {
                        echo "<div class='battle-card' data-battle='$key' onclick='highlightBattle(this)'>$tekst</div>";
                    }
                    ?>
                </div>

                <form method="post" class="answer-form">
                    <p><strong>Welke veldslag wordt ook wel de "Driekeizerslag" genoemd?</strong></p>
                    <input type="text" name="answer" class="answer-input" placeholder="Slag bij..." required>
                    <br>
                    <button type="submit" class="submit-btn">Bevestig Antwoord</button>
                </form>

            <?php endif; ?>

            <?php if ($showHint): ?>
                <div class="hint">
                    <strong>❌ Dat is niet correct!</strong><br>
                    💡 <strong>Hint:</strong> <?= htmlspecialchars($currentQuestion['hint']) ?>
                </div>
            <?php endif; ?>
        </div>

        <a href="index.php" class="back-link">🏠 Terug naar start</a>
    </div>
    <script src="vragen.js"></script>
</body>
</html>