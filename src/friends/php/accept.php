<?php
require_once '../../content/initialize.php';

session_start();
include('../../database/connect.php');
include('../../content/logincheck.php');
require_once('../../repositories/repositorymanager.php');

$friendsRepository = RepositoryManager::get()->getFriendsRepository();

$db = getDatabase();

if ($_SESSION['username'] === $_GET['u']) {
    header('Location: ../index.php?err=you');
    exit;
}

$alreadyFriends = $friendsRepository->alreadyFriends($_SESSION['username'], $_GET['u']);
if (!$alreadyFriends) {
    $exists = $db->query("SELECT request_id
        FROM friend_requests
        WHERE (sender_username=:sender_id AND receiver_username = :receiver_username)
        OR (receiver_username=:sender_id AND sender_username = :receiver_username)", [
            ':receiver_username' => $_GET['u'],
            ':sender_id' => $_SESSION['username']
        ]);

    if (isset($exists[0]['request_id'])) {
        $db->execute("DELETE FROM friend_requests
            WHERE (sender_username=:sender_id AND receiver_username = :receiver_username)
            OR (receiver_username=:sender_id AND sender_username = :receiver_username)", [
                ':receiver_username' => $_GET['u'],
                ':sender_id' => $_SESSION['username']
            ]);

        $db->execute("INSERT INTO friends 
            (user1,user2,bested) 
            VALUES (:user1,:user2,false)", [
                ':user1' => $_SESSION['username'],
                ':user2' => $_GET['u']
            ]);

        $db->execute("INSERT INTO friends 
            (user1,user2,bested) 
            VALUES (:user1,:user2,false)", [
                ':user1' => $_GET['u'],
                ':user2' => $_SESSION['username']
            ]);

        $user1bp = $db->queryFirstColumn("SELECT boostpoints
            FROM members
            WHERE username=:username", 0, [
            ':username' => $_SESSION['username']
        ]);

        $user2bp = $db->queryFirstColumn("SELECT boostpoints
            FROM members
            WHERE username=:username", 0, [
            ':username' => $_GET['u']
        ]);

        $newuser1bp = $user1bp + 5;
        $newuser2bp = $user2bp + 5;

        $db->execute("UPDATE members
            SET boostpoints=:bp
            WHERE username=:username", [
            ':bp' => $newuser1bp,
            ':username' => $_SESSION['username']
        ]);

        $db->execute("UPDATE members
            SET boostpoints=:bp
            WHERE username=:username", [
            ':bp' => $newuser2bp,
            ':username' => $_GET['u']
        ]);

        header('Location: ../index.php');
    } else {
        header('Location: ../index.php?err=before');
    }
} else {
    header('Location: ../index.php?err=that');
}
