<?php
require_once '../../content/initialize.php';

session_start();

if (isset($_SESSION['username'])) {
    require_once('../../database/connect.php');

    $t = time();
    $db = getDatabase();
    $db->execute("UPDATE members SET lastlogin=:t WHERE username=:username", [
        ':t' => $t,
        ':username' => $_SESSION['username']
    ]);

    $db->execute("UPDATE members SET status='creating' WHERE username=:username", [
        ':username' => $_SESSION['username']
    ]);

    echo "keepalive=1";
} else {
    echo "keepalive=0";
}
