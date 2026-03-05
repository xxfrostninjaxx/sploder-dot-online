<?php
require_once '../../content/initialize.php';

require_once(__DIR__ . '/../../content/logincheck.php');
require_once(__DIR__ . '/../../database/connect.php');

$db = getDatabase();
$sql = "SELECT username FROM award_requests WHERE id = :id";
$username = $db->queryFirstColumn($sql, 0, [
  ':id' => $_GET['id']
]);

if ($username != $_SESSION['username']) {
    header("Location: ../index.php");
    die();
}

// Delete the award off award_requests
$sql = "DELETE FROM award_requests WHERE id = :id";
$db->execute($sql, [
  ':id' => $_GET['id']
]);
header("Location: ../index.php");
