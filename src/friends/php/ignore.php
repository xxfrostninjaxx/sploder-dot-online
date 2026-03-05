<?php
require_once '../../content/initialize.php';

session_start();
include('../../database/connect.php');

$db = getDatabase();
$statement2 = $db->execute("DELETE FROM friend_requests
    WHERE (sender_username=:sender_id AND receiver_username = :receiver_username)
    OR (receiver_username=:sender_id AND sender_username = :receiver_username)", [
    ':receiver_username' => $_GET['u'],
    ':sender_id' => $_SESSION['username']
    ]);

header('Location: ../index.php');
