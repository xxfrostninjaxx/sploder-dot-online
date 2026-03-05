<?php
require_once '../content/initialize.php';

session_start();
if (isset($_SESSION['username'])) {
    $t = time();
    $status = $_GET['status'];
    if (!isset($status)) {
        $status = "online";
    }
    require_once('../database/connect.php');
    $db = getDatabase();
    $db->execute("UPDATE members
        SET lastlogin=:t, status=:status
        WHERE username=:username", [
        ':t' => $t,
        ':username' => $_SESSION['username'],
        ':status' => $status
    ]);
}
