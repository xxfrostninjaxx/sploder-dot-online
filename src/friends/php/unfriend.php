<?php
require_once '../../content/initialize.php';

session_start();
require_once('../../database/connect.php');

$db = getDatabase();

$db->execute("DELETE FROM friends
    WHERE (user1=:sender_id AND user2 = :receiver_username) 
    OR (user2=:sender_id AND user1 = :receiver_username)", [
        ':receiver_username' => $_GET['u'],
        ':sender_id' => $_SESSION['username']
    ]);

$db->execute("DELETE FROM friends
    WHERE (user1=:sender_id AND user2 = :receiver_username)
    OR (user2=:sender_id AND user1 = :receiver_username)", [
        ':receiver_username' => $_SESSION['username'],
        ':sender_id' => $_GET['u']
    ]);

header('Location: ../index.php');
