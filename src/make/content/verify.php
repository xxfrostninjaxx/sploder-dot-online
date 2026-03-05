<?php
// Check whether user is logged in
require(__DIR__.'/../../content/logincheck.php');
// Check whether user owns the game
require(__DIR__.'/../../database/connect.php');
$db = getDatabase();

$id = intval($_POST['id']);

$qs = "SELECT author FROM games WHERE g_id = :id";
$result = $db->queryFirst($qs, [':id' => $id]);
if ($_SESSION['username'] != $result['author']) {
    // Send 403
    http_response_code(403);
}
