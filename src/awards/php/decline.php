<?php
require_once '../../content/initialize.php';

require_once(__DIR__ . '/../../content/logincheck.php');
require_once(__DIR__ . '/../../database/connect.php');

$db = getDatabase();

// Check if author is actually the receiver, if not, die
$membername = $db->queryFirstColumn("SELECT membername
    FROM awards
    WHERE id = :id", 0, [
  ':id' => $_GET['id']
]);

if ($membername !== $_SESSION['username']) {
    header("Location: ../index.php");
    die();
}
// Delete the award off award_requests
$db->execute("DELETE FROM award_requests WHERE id = :id", [
  ':id' => $_GET['id']
]);

header("Location: ../index.php");
