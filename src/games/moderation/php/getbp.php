<?php
require_once '../../../content/initialize.php';


include('verify.php');
require_once("../../../database/connect.php");
$db = getDatabase();

$username = $_POST['username'];

// Check whether user actually exists
$sql = "SELECT COUNT(*) FROM members WHERE username=:username";
$count = $db->queryFirstColumn($sql, 0, [
    ':username' => $username
]);
if ($count == 0) {
    header("Location: ../index.php?err=User does not exist");
    die();
}

// Get boost points
$bp = $db->queryFirstColumn("SELECT boostpoints
    FROM members
    WHERE username=:username", 0, [
    ':username' => $username
]);

// Send header with boost points
include('log.php');
logModeration('checked boost points', 'of ' . $username . ' which is ' . $bp, 1);
header("Location: ../index.php?bp=" . $bp);
