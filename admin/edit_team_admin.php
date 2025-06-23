<?php
if (!isset($_POST['TeamID'])) {
    die('Geen team geselecteerd.');
}
include_once "../dbcon.php";

// set integer of team id
$teamId = (int) $_POST['TeamID'];

// get teamname
$sqlTeam = "SELECT DISTINCT teamnaam FROM teams WHERE TeamID = :teamid LIMIT 1";
$stmtTeam = $conn->prepare($sqlTeam);
$stmtTeam->execute(['teamid' => $teamId]);
$teamData = $stmtTeam->fetch(PDO::FETCH_ASSOC);

if (!$teamData) {
    die('Team niet gevonden.');
}

// get every username
$sqlUsers = "SELECT username FROM users ORDER BY username";
$stmtUsers = $conn->prepare($sqlUsers);
$stmtUsers->execute();
$allUsers = $stmtUsers->fetchAll(PDO::FETCH_COLUMN);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $ids = $_POST['id'] ?? [];
    $usernames = $_POST['username'] ?? [];
    $scores = $_POST['score'] ?? [];

    if (count($ids) === count($usernames) && count($ids) === count($scores)) {
        $sqlUpdate = "UPDATE teams SET username = :username, score = :score WHERE id = :id";
        $stmtUpdate = $conn->prepare($sqlUpdate);

        foreach ($ids as $index => $id) {
            // Check of username bestaat in users (extra check)
            if (!in_array($usernames[$index], $allUsers, true)) {
                die("Ongeldige username: " . htmlspecialchars($usernames[$index]));
            }
            $stmtUpdate->execute([
                'username' => $usernames[$index],
                'score' => $scores[$index],
                'id' => (int)$id,
            ]);
        }
        echo "<p>Team gegevens bijgewerkt.</p>";
        echo "<p><a href='edit_team.php'>Terug naar teams</a></p>";
        exit;
    } else {
        echo "<p>Fout bij het verwerken van de invoer.</p>";
    }
}

// get team members
$sqlMembers = "SELECT id, username, score FROM teams WHERE TeamID = :teamid ORDER BY id";
$stmtMembers = $conn->prepare($sqlMembers);
$stmtMembers->execute(['teamid' => $teamId]);
$members = $stmtMembers->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8" />
    <title>Team bewerken - <?= htmlspecialchars($teamData['teamnaam']) ?></title>
</head>

<body>
    <h2>Team bewerken: <?= htmlspecialchars($teamData['teamnaam']) ?></h2>

    <form method="post" action="">
        <input type="hidden" name="TeamID" value="<?= $teamId ?>">

        <table border="1" cellpadding="5" cellspacing="0">
            <thead>
                <tr>
                    <th>Speler ID</th>
                    <th>Username</th>
                    <th>Score</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($members as $member): ?>
                    <tr>
                        <td><?= $member['id'] ?>
                            <input type="hidden" name="id[]" value="<?= $member['id'] ?>">
                        </td>
                        <td>
                            <select name="username[]" required>
                                <?php foreach ($allUsers as $user): ?>
                                    <option value="<?= htmlspecialchars($user) ?>" <?= $user === $member['username'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($user) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </td>
                        <td>
                            <input type="number" name="score[]" value="<?= (int)$member['score'] ?>" required>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <br>
        <button type="submit" name="update">Opslaan</button>
        <a href="edit_team.php">Terug</a>
    </form>
</body>

</html>