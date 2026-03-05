<?php
require_once '../../content/initialize.php';

include('../../content/logincheck.php');
include('../../database/connect.php');
require_once('../../repositories/repositorymanager.php');

$friendsRepository = RepositoryManager::get()->getFriendsRepository();
$userRepository = RepositoryManager::get()->getUserRepository();
$username = $_GET['username'];
$db = getDatabase();
$alreadyFriends = $friendsRepository->alreadyFriends($_SESSION['username'], $username);
$isolatedreceiver = $userRepository->isIsolated($username);
if ($isolatedreceiver) {
    header('Location: ../index.php?err=isolated');
    exit;
}
if (!$alreadyFriends) {
    $qs2 = "SELECT userid FROM members WHERE username=:user";
    $receiver = $db->query($qs2, [
        ':user' => $username
    ]);
    if (isset($receiver[0]['userid'])) {
        $receiver = $receiver[0]['userid'];
    } else {
        header('Location: ../index.php?err=no');
        exit;
    }
    $qs2 = "SELECT request_id FROM friend_requests WHERE (sender_username=:sender_id AND receiver_username = :receiver_id) OR ((receiver_username=:sender_id AND sender_username = :receiver_id))";
    $exists = $db->query($qs2, [
        ':receiver_id' => $username,
        ':sender_id' => $_SESSION['username']
    ]);

    if (isset($exists[0]['request_id'])) {
        header('Location: ../index.php?err=sent');
    } elseif ($receiver == $_SESSION['userid']) {
        header('Location: ../index.php?err=you');
    } elseif ($receiver != $_SESSION['userid']) {
        $qs2 = 'INSERT INTO friend_requests 
            (sender_id, receiver_id, sender_username, receiver_username) 
            VALUES (:sender_id, :receiver_id, :sender_username, :receiver_username)';
        $statement2 = $db->execute($qs2, [
            ':sender_id' => $_SESSION['userid'],
            ':receiver_id' => $receiver,
            ':sender_username' => $_SESSION['username'],
            ':receiver_username' => $_GET['username']
        ]);
        header('Location: ../index.php?err=suc');
    }
} else {
    header('Location: ../index.php?err=that');
}