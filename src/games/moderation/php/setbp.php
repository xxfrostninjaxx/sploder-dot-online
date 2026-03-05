<?php
require_once '../../../content/initialize.php';


include('verify.php');
require_once("../../../database/connect.php");

$username = $_POST['username'];
$db = getDatabase();

// Check whether username exists
$count = $db->queryFirstColumn("SELECT COUNT(*) FROM members WHERE username=:username", 0, [
    ':username' => $username
]);
if ($count == 0) {
    header("Location: ../index.php?err=User does not exist");
    die();
}
// Get boost points
$oldbp = $db->queryFirstColumn("SELECT boostpoints FROM members WHERE username=:username", 0, [
    ':username' => $username
]);
// Set boost points
if (
    $db->execute("UPDATE members SET boostpoints = :boostpoints WHERE username=:username", [
    ':boostpoints' => $_POST['bp'],
    ':username' => $username
    ])
) {
    include('log.php');
    logModeration('set boost points', 'from ' . $oldbp . ' to ' . $_POST['bp'] . ' for ' . $username, 2);
    header("Location: ../index.php?msg=Boost points set successfully");
} else {
    header("Location: ../index.php?err=There was an error while setting the boost points");
}
