<?php
require_once '../../content/initialize.php';

session_start();
require_once('../../database/connect.php');

$db = getDatabase();
$db->execute("UPDATE friends
    SET bested=false
    WHERE (user1=:sender_id AND user2=:receiver_id)", [
        ':sender_id' => $_SESSION['username'],
        ':receiver_id' => $_GET['u']
    ]);

header('Location: ../index.php');
