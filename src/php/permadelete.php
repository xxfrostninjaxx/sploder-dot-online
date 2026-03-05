<?php
require_once '../content/initialize.php';

session_start();

header('Content-Type: text/xml');
$id = (int)filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);

require_once('../database/connect.php');

$db = getDatabase();
$result2 = $db->query("SELECT author FROM games WHERE g_id=:id", [
    ':id' => $id,
]);

if ($_SESSION["username"] == $result2[0]["author"]) {
    $db->execute("DELETE FROM games WHERE g_id=:id", [
        ':id' => $id,
    ]);
    `rm -rf ../projects/proj$id`;
    header('Location: ../dashboard/trash.php');
} else {
    echo "There was an error while deleting your game.";
}
