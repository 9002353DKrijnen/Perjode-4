<?php
if(isset($_POST['TeamID'])){
    include_once '../dbcon.php';
    $TeamID = $_POST['TeamID'];
    $sql = "DELETE FROM teams WHERE TeamID = :TeamID";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':TeamID', $TeamID);
    $stmt->execute();
    header("Location: edit_team.php");
    exit;
}

?>