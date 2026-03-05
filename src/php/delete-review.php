<?php
require_once '../content/initialize.php';

require_once('../repositories/repositorymanager.php');
session_start();
$userRepository = RepositoryManager::get()->getUserRepository();
$perms = $userRepository->getUserPerms($_SESSION['username']);
if ($perms === null || $perms === '' || !str_contains($perms, 'R')) {
    die("Haxxor detected");
}
$s = $_GET['s'] ?? '';
if ($s == '') {
    header('Location: /games/reviews.php');
    die();
}
$s = explode("_", $s);
$userId = $s[0];
$gameId = $s[1];
$gameRepository = RepositoryManager::get()->getGameRepository();
$gameRepository->deleteReview($_SESSION['userid'], $gameId);
header('Location: /games/reviews.php?deleted');