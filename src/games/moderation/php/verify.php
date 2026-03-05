<?php
// Login check
include(__DIR__ . '/../../../content/logincheck.php');

// Check whether the user is actually a moderator and not haxxor
$username = $_SESSION['username'];
// Use dir to include database
include(__DIR__ . '/../../../database/connect.php');
$db = getDatabase();
$qs = "SELECT perms FROM members WHERE username=:username";
$perms = $db->queryFirstColumn($qs, 0, [
    ':username' => $username
]);
// If perms includes M, then the user is a moderator, else they are haxxor
if ($perms[0] === null || $perms[0] === '' || !str_contains($perms[0], 'M')) {
    die("Haxxor detected");
}
