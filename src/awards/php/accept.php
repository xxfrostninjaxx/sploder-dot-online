<?php
require_once '../../content/initialize.php';


require_once(__DIR__ . '/../../content/logincheck.php');
require_once(__DIR__ . '/../../database/connect.php');

$db = getDatabase();

// Check if author is actually the receiver, if not, die
$sql = "SELECT membername FROM award_requests WHERE id = :id";
$membername = $db->queryFirstColumn($sql, 0, [
  ':id' => $_GET['id']
]);

if ($membername !== $_SESSION['username']) {
    header("Location: ../index.php");
    die();
}

// TODO: Wrap both statements in a transaction
// Transfer the award off award_requests to awards and remove it from award_requests in only 1 sql query
$sql = "INSERT INTO awards 
  (username, membername, level, category, style, material, icon, color, message) 
SELECT username, membername, level, category, style, material, icon, color, message 
FROM award_requests 
WHERE id = :id";
$db->execute($sql, [
  ':id' => $_GET['id']
]);

$db->execute("DELETE FROM award_requests WHERE id = :id", [
  ':id' => $_GET['id']
]);

header("Location: ../index.php");
