<?php
require_once '../content/initialize.php';

include('../database/connect.php');
include('includes/votes.php');
$g_id = $_GET['ssid'];
$score = $_GET['score'];
session_id($_GET['PHPSESSID']);
session_start();
$username = $_SESSION['username'];
$db = getDatabase();
if (!vote_check($username, $g_id)) {
    $sql = "INSERT INTO votes (g_id, username, score) VALUES (:g_id, :username, :score)";
} else {
    $sql = "UPDATE votes SET score = :score WHERE g_id = :g_id AND username = :username";
}
$statement = $db->execute($sql, [
    ':g_id' => $g_id,
    ':username' => $username,
    ':score' => $score
]);

$votes = get_votes($g_id);
$average = round($votes['avg']);
echo "&total_votes={$votes['count']}&average={$average}";
