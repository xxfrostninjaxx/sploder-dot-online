<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

if (!isset($_SESSION['username'])) {
    session_destroy();
    header('Location: /accounts/login.php');
    die();
}
