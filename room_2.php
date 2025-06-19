<?php
session_start(); // Start een sessie om gebruikersgegevens op te slaan (zoals loginstatus)

// Check of de gebruiker is ingelogd, zo niet, stuur door naar loginpagina
if (!isset($_SESSION["user_id"])) {
    header("Location: user/login.php");
    exit;
}

include 'dbcon.php'; // Databaseverbinding includen

// --- TIMER INSTELLEN ---

// Als dit de eerste keer is dat deze kamer wordt bezocht, zet starttijd en duur in sessie
if (!isset($_SESSION['start_time'])) {
    $_SESSION['start_time'] = time();      // Huidige timestamp opslaan als starttijd
    $_SESSION['duration'] = 300;           // Duur van de timer = 300 seconden = 5 minuten
}

// Controleer of de timer al verlopen is
if (isset($_SESSION['start_time']) && (time() - $_SESSION['start_time'] > $_SESSION['duration'])) {
    // Timer is verlopen, stuur naar de 'verloren' pagina
    header("Location: lose.php");
    exit();
}

// Bereken hoeveel tijd er nog over is (in seconden)
$timeleft = ($_SESSION['start_time'] + $_SESSION['duration']) - time();
$minutes = floor($timeleft / 60); // Bereken resterende minuten
$seconds = $timeleft % 60;        // Bereken resterende seconden

// --- VRAGEN VOOR KAMER 2 OPHALEN ---

$roomId = 2; // We zijn in kamer 2
$questionIndex = $_SESSION['questionIndex2'] ?? 0; // Welke vraag moet getoond worden? Standaard 0

// Haal alle vragen van kamer 2 uit de database, gesorteerd op id
$stmt = $conn->prepare("SELECT * FROM questions WHERE roomId = :roomId ORDER BY id ASC");
$stmt->execute(['roomId' => $roomId]);
$questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Als er geen vragen gevonden zijn, stop met een foutmelding
if (count($questions) === 0) {
    echo "Geen vragen gevonden voor deze kamer.";
    exit;
}

$currentQuestion = $questions[$questionIndex]; // De huidige vraag die getoond moet worden
$showHint = false; // Variabele om te bepalen of hint getoond moet worden

// --- ANTWOORD VERWERKEN ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Controleer opnieuw of de tijd niet verstreken is bij het verzenden van antwoord
    if (time() - $_SESSION['start_time'] > $_SESSION['duration']) {
        header("Location: lose.php");
        exit();
    }

    // Lees het antwoord van de gebruiker, trim en maak lowercase voor makkelijke vergelijking
    $userAnswer = trim(strtolower($_POST['answer']));

    // Bij de derde vraag (index 2) zijn meerdere antwoorden geldig
    if ($questionIndex == 2) {
        $validAnswers = ['slag bij austerlitz', 'austerlitz'];
        if (in_array($userAnswer, $validAnswers)) {
            // Antwoord is correct, ga naar volgende vraag
            $questionIndex++;
            $_SESSION['questionIndex2'] = $questionIndex;

            // Zijn alle vragen beantwoord? Zo ja, reset index en ga naar winpagina
            if ($questionIndex >= count($questions)) {
                $_SESSION['questionIndex2'] = 0;
                header("Location: win.php");
                exit;
            }
        } else {
            $showHint = true; // Fout antwoord, hint tonen
        }
    } else {
        // Voor overige vragen: exact vergelijken met het juiste antwoord uit database
        $correctAnswer = strtolower($currentQuestion['answer']);
        if ($userAnswer === $correctAnswer) {
            // Antwoord correct, volgende vraag
            $questionIndex++;
            $_SESSION['questionIndex2'] = $questionIndex;

            // Alles beantwoord? Door naar winpagina
            if ($questionIndex >= count($questions)) {
                $_SESSION['questionIndex2'] = 0;
                header("Location: win.php");
                exit;
            }
        } else {
            $showHint = true; // Fout antwoord, hint tonen
        }
    }
}
?>

<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="vragen.css"> <!-- Stijlblad voor pagina -->
    <title>Napoleon's Geheimen - Kamer 2</title>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏛️ Napoleon's Geheimen - Kamer 2</h1>
            <!-- Timer toont hoeveel tijd er nog over is -->
            <div class="timer" id="timeRemaining" value="<?= $timeleft ?>">
                <?php echo $minutes . " minuten en " . $seconds . " seconden"; ?>
            </div>
        </div>

        <div class="question-container">
            <!-- Toon de huidige vraag -->
            <h2><?= htmlspecialchars($currentQuestion['question']) ?></h2>

            <!-- Verschillende vragen, met eigen content per vraag -->
            <?php if ($questionIndex == 0): ?>
                <!-- Vraag 1: Interactieve verhalen die je kunt openen -->
                <div class="instruction">
                    <p><strong>🔍 Zoek de juiste geschiedenis!</strong> Klik op de verhalen om ze te ontdekken. Eén verhaal bevat de sleutel tot het antwoord...</p>
                </div>
                
                <div class="code-puzzle">
                    <?php
                    // Verschillende verhalen als 'kaartjes' die je kunt klikken
                    $verhalen = [
                        "correct" => "In het oude Frankrijk heerste chaos in het rechtssysteem. Keizer Napoleon gaf de opdracht om alles te ordenen in één duidelijke wetbundel. Die bundel werd bekend als de <strong>Code Napoleon</strong>. Het bracht orde in de maatschappij en werd model voor veel andere landen.",
                        "fake1" => "Na de revolutie ontstond er verwarring over wetten. Een commissie bedacht toen de Burgercode, gebaseerd op Romeins recht. Deze werd echter nooit officieel ingevoerd door Napoleon.",
                        "fake2" => "In Duitsland ontwikkelde men de Grundgesetz, maar dat gebeurde jaren na Napoleon. Het werd de grondwet van de Bondsrepubliek en had weinig met Napoleon te maken.",
                        "fake3" => "Napoleon wilde wetten voor iedereen. Hij stelde juristen aan die een overzicht maakten, maar noemden het toen 'Wettencollectie'. Later werd dit afgekeurd door de senaat.",
                        "fake4" => "Sommige landen volgden het Engelse model van common law. Dit systeem had geen geschreven code zoals Napoleon wilde introduceren in Frankrijk.",
                        "fake5" => "In Spanje werd gedacht aan de 'Código Civil'. Dat was vergelijkbaar met wat Napoleon wilde, maar het had een andere naam en werd pas veel later ontwikkeld."
                    ];
                    
                    // Loop door verhalen en maak voor elk een klikbare div
                    foreach ($verhalen as $key => $tekst) {
                        echo "<div class='story-card' data-story='$key' onclick='revealStory(this)'>
                                <div class='story-content' style='display:none;'>$tekst</div>
                                <div class='story-preview'>📜 Klik om te ontdekken</div>
                              </div>";
                    }
                    ?>
                </div>

                <!-- Formulier om antwoord in te vullen -->
                <form method="post" class="answer-form">
                    <p><strong>Wat is de naam van Napoleon's beroemde wetboek?</strong></p>
                    <input type="text" name="answer" class="answer-input" placeholder="Typ hier je antwoord..." required>
                    <br>
                    <button type="submit" class="submit-btn">Bevestig Antwoord</button>
                </form>

            <?php elseif ($questionIndex == 1): ?>
                <!-- Vraag 2: Interactieve kaart voor ballingschap -->
                <div class="mystery-container">
                    <div class="instruction">
                        <p><strong>🏝️ Het Mysterie van de Ballingschap</strong> Klik op de geheimzinnige kaart om het verhaal van Napoleon's ontsnapping te onthullen...</p>
                    </div>
                    
                    <div class="mystery-image">
                        <img src="admin/img/vraag2.png" alt="Mysteriekaart" onclick="revealMystery()">
                        <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);
                                    font-size: 24px; text-shadow: 2px 2px 4px rgba(0,0,0,0.8); pointer-events: none;">
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
                <!-- Vraag 3: Veldslagen lezen en juiste naam invullen -->
                <div class="instruction">
                    <p><strong>⚔️ De Grote Veldslagen</strong> Lees de verhalen van Napoleon's veldslagen. Eén van deze beschrijft zijn grootste overwinning - de "Driekeizerslag"!</p>
                </div>
                
                <div class="battle-stories">
                    <?php
                    // Beschrijvingen van veldslagen
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

            <!-- Als het antwoord fout was, toon hint -->
            <?php if ($showHint): ?>
                <div class="hint">
                    <strong>❌ Dat is niet correct!</strong><br>
                    💡 <strong>Hint:</strong> <?= htmlspecialchars($currentQuestion['hint']) ?>
                </div>
            <?php endif; ?>
        </div>

        <a href="index.php" class="back-link">🏠 Terug naar start</a>
    </div>

    <!-- JavaScript bestand voor interactieve elementen -->
    <script src="vragen.js"></script>
</body>
</html>
