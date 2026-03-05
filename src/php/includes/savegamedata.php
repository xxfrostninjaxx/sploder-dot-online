<?php
session_id($_GET['PHPSESSID']);
session_start();
if (isset($_SESSION['loggedin'])) {
    include('../content/checkban.php');
    if (checkBan($_SESSION['username'])) {
        die('<message result="failed" message="You are banned and will not be able to publish games."/>');
    }
    $xml = $_POST['xml'] ?? file_get_contents('php://input');
    $xml2 = simplexml_load_string(strval($xml)) or die("INVALID XML FILE!!");
    if(!isset($_GET['comments']) || !isset($_GET['private'])){
        die('<message result="failed" message="Please save your game again."/>');
    }
    $author = $_SESSION['username'];
    $xml_author = urldecode($xml2->attributes()['title']);
    if ($author != $xml2->attributes()['author']) {
        die('<message result="failed" message="Haxxor detected"/>');
    }
    $comments = $_GET['comments'];
    $private = $_GET['private'];
    $id = (int)filter_var($_GET['projid'], FILTER_SANITIZE_NUMBER_INT);

    require_once('../database/connect.php');
    $db = getDatabase();

    require_once('../repositories/repositorymanager.php');
    $gameRepository = RepositoryManager::get()->getGameRepository();
    $challengeRepository = RepositoryManager::get()->getChallengesRepository();


    if (!$gameRepository->verifyOwnership($id, $_SESSION['username'])) {
        http_response_code(403);
        die('<message result="failed" message="You do not own this game!"/>');
    }

    $challengeRepository->unverifyChallenge($id);

    $gameRepository->publishGame(
        $id,
        $private,
        $comments
    );
    
    $project_path = "../users/user" . $_SESSION['userid'] . "/projects/proj" . $id . "/";
    file_put_contents($project_path . "game.xml", $xml);
    echo '<message result="success" id="proj' . $id . '" pubkey="' . $_SESSION['userid'] . '_' . $id . '" message="Success"/>';
} else {
    echo '<message result="failed" message="The session ID is incorrect! Log out and log in again."/>';
}