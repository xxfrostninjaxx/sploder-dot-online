<?php
require_once '../content/initialize.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

require_once(__DIR__ . "/../repositories/repositorymanager.php");
require_once(__DIR__ . "/../accounts/getip.php");

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

$gameRepository = RepositoryManager::get()->getGameRepository();
if(!str_contains($_GET['g'], "_")){
    $username = $_SESSION['username'] ?? "DEMO";
    echo "&username={$username}&difficulty=5&rating=3";
    die();
}
$separated = explode("_", $_GET['g']);
$userId = $separated[0];
$gameId = $separated[1];

if ($userId !== $gameRepository->getUserId($gameId)) {
    echo 'Error';
    die();
}

$gameData = $gameRepository->getGameData($gameId);
$gameRepository->trackView($gameId, getVisitorIp(), $_SESSION['userid'] ?? null);

echo "&username={$gameData->author}&difficulty=" . $gameData->difficulty . "&rating={$gameData->avgScore}";