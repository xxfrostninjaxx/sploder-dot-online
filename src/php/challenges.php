<?php
require_once '../content/initialize.php';

session_start();
require_once('../repositories/repositorymanager.php');

$challengesRepository = RepositoryManager::get()->getChallengesRepository();
$userRepository = RepositoryManager::get()->getUserRepository();
$gameRepository = RepositoryManager::get()->getGameRepository();

if(isset($_POST['choice'])) {
    $mode = $_POST['choice'];
    $mode = $mode == 0 ? true : false;
    $challenge = $_POST['challenge'];
    $prize = $_POST['prize'];
    $winners = $_POST['winners'];
    $g_id = $_POST['g_id'];

    if(!$mode) {
        $gameSWF = $gameRepository->getGameSWF($g_id);
        if(!($gameSWF == 5 || $gameSWF == 7)) {
            $mode = true;
        }
    }

    $challengeInfo = $challengesRepository->getChallengeInfo($g_id);
    
    // If a challenge already exists, redirect to the challenges page
    if($challengeInfo) {
        header('Location: /games/challenges.php');
        exit();
    }

    $userBoostPoints = $userRepository->getBoostPoints($_SESSION['userid']);
    $cost = $prize * $winners;

    if ($userBoostPoints < $cost || $cost < 150 || $prize < 50 || $winners < 1 || $challenge < 1) {
        header("Location: /games/challenges.php?error=invalid_input");
        exit();
    }

    // Check if user has enough boost points
    $userBoostPoints = $userRepository->getBoostPoints($_SESSION['userid']);
    if($userBoostPoints < $prize) {
        header("Location: /games/challenges.php?error=not_enough_boostpoints");
        exit();
    }

    // Reduce boost points
    $userRepository->removeBoostPoints($_SESSION['userid'], $cost);

    $challengesRepository->addChallenge($g_id, $mode, $challenge, $prize, $winners);


    header("Location: /games/challenges.php?accept=".$_SESSION['userid']."_".$g_id);
} else if(isset($_POST['accept'])) {
    $s = explode("_",$_POST['s']);
    if($challengesRepository->verifyIfSIsCorrect($s[1], $s[0])) {
        $challenge_id = $challengesRepository->getChallengeId($s[1]);
        if(!($challengesRepository->hasWonChallenge($s[1], $_SESSION['userid']))) {
            $_SESSION['challenge'] = $challenge_id;
            header("Location: /games/play.php?s=" . $s[0] . "_" . $s[1] . "&challenge=" . $challenge_id);
        }
    }
}