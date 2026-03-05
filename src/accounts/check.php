<?php
require_once '../content/initialize.php';

require('logincheck.php');
require_once('../database/connect.php');

if (session_status() === PHP_SESSION_ACTIVE) {
    session_destroy();
}


$username = $_POST['username'] ?? null;
$password = $_POST['password'] ?? null;
if (strlen($password) > 25) {
    $password = substr($password, 0, 25);
}
$db = getDatabase();

$user = $db->queryFirst(
    "SELECT password,userid FROM members WHERE username=:user LIMIT 1",
    [
    ':user' => mb_strtolower($username)
    ]
);

if ($user == null || !password_verify($password, $user['password'])) {
    header('Location: login.php?err=no');
    die();
    return;
}

session_start();
$_SESSION['loggedin'] = true;
$_SESSION['username'] = mb_strtolower($username);
$_SESSION['userid'] = $user['userid'];

session_regenerate_id();
$_SESSION['PHPSESSID'] = session_id();
$t = time();

$query = "
UPDATE members 
SET 
  lastlogin=:t,
  status='online',
  lastpagechange=:t
WHERE username=:username
";
$db->execute($query, [
  ':t' => $t,
  ':username' => $_SESSION['username']
]);

header('Location: ../accounts/account.php');
