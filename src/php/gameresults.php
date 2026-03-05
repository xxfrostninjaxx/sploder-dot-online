<?php
require_once '../content/initialize.php';

require_once('../repositories/repositorymanager.php');

$challengesRepository = RepositoryManager::get()->getChallengesRepository();
$userRepository = RepositoryManager::get()->getUserRepository();
$gameRepository = RepositoryManager::get()->getGameRepository();

// Credits to Classic Customs for most of the difficulty formula
function difficulty(int $wins, int $loss): int {
    $c = $wins + $loss;
    if ($c === 0) {
        return 5; // no games played
    }

    $r = $wins / $c;

    // raw difficulty purely by ratio
    $d_raw = 10 - (9 * $r);

    // weight grows with more games (tune N = 20, or whatever feels right)
    $N = 20;
    $w = min(1, $c / $N);

    // blend baseline 5.5 with raw difficulty
    $x = (1 - $w) * 5.5 + $w * $d_raw;

    // round to whole number and clamp
    $x = round($x);
    return max(1, min(10, $x));
}
    $hash = $_GET['ax'];
    $gtm = filter_var($_POST['gtm'], FILTER_VALIDATE_INT);

    $w = filter_var($_POST['w'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
    $w = $w ? 'true' : 'false';

    $id = explode("_", $_POST['pubkey']);
    $id[0] = filter_var($id[0], FILTER_VALIDATE_INT);
    $id[1] = filter_var($id[1], FILTER_VALIDATE_INT);
    require_once(__DIR__ . '/verifyscore.php');
    // Check whether verifyscore just spits out true with 0 validation
    $verifiedScore = false;
    if (verifyScore("novalid", $id[0], $id[1], $w, $gtm)) {
        // Attempt to validate using the local modified md5 javascript
        // This is insecure and only for local development use ONLY
        `{$hash}`;
    }
    $verifiedScore = verifyScore($hash, $id[0], $id[1], $w, $gtm);

if ($verifiedScore) {
    session_start();
    if (!isset($_SESSION['username'])) {
        die("&success=true");
    }
    if (isset($_POST['scorebased']) && $_POST['scorebased'] == '1') {
        $insert = filter_var($_POST['score'], FILTER_VALIDATE_INT);
    } else {
        $insert = $gtm;
    }
    require_once('../database/connect.php');
    $db = getDatabase();
    $db->execute("INSERT INTO leaderboard
        (username, pubkey, gtm, w)
        VALUES (:username, :pubkey, :gtm, :w)", [
        ':username' => $_SESSION['username'],
        ':pubkey' => $id[1],
        ':gtm' => $insert,
        ':w' => $w
    ]);

    if ($w == 'true') {
        $userRepository->addBoostPoints($_SESSION['userid'], min(25, round($gtm/60)));
    }

    if(isset($_SESSION['challenge'])){
        $challengeId = $challengesRepository->getChallengeId($id[1]);
        if($challengeId == $_SESSION['challenge']) {
            $challengeId = $_SESSION['challenge'];
        } else {
            $challengeId = null;
        }
    } else {
        $challengeId = null;
    }
    
    if($challengeId != null && $w == 'true') {
        // Get challenge info to confirm if the game is a challenge and check the requirements
        $challengeInfo = $challengesRepository->getChallengeInfo($id[1]);
        $challenge = $challengeInfo['challenge'];
        $prize = $challengeInfo['prize'];
        $mode = $challengeInfo['mode'];

        // Verify challenge ID
        $isValidChallenge = $challengesRepository->verifyChallengeId($id[1], $challengeId, $_SESSION['challenge'] ?? -1);
        // Verify if the challenge requirements are met
        if ($mode) {         
            if($gtm > $challenge) {
                $isValidChallenge = false;
            }
        } else {
            $score = filter_var($_POST['score'], FILTER_VALIDATE_INT);
            if($score < $challenge) {
                $isValidChallenge = false;
            }
        }

        if($challengesRepository->hasWonChallenge($id[1], $_SESSION['userid'])) {
            $isValidChallenge = false;
        }
        
        // If the game is a challenge, insert the result into the challenges table
        if ($isValidChallenge) {   
            // Check if the user is the owner of the game
            $isOwner = $gameRepository->verifyOwnership($id[1], $_SESSION['username']);
            // If the user is the owner, update the challenge as verified
            if ($isOwner) {
                $challengesRepository->verifyChallenge($challengeId);
            } else {
                $challengesRepository->addChallengeWinner($id[1], $_SESSION['userid']);
                $userRepository->addBoostPoints($_SESSION['userid'], $prize);
            }
        }
    }
    echo "&success=true";
    // Update difficulty in game table
    $result2 = $db->query("
    SELECT 
        SUM(CASE WHEN w = TRUE THEN 1 ELSE 0 END) AS wins,
        SUM(CASE WHEN w = FALSE THEN 1 ELSE 0 END) AS losses
    FROM leaderboard 
    WHERE pubkey = :g_id;
    ",  [
        ':g_id' => $id[1]
        ])[0];

    $db->execute("UPDATE games
        SET difficulty = :difficulty
        WHERE g_id = :g_id", [
        ':difficulty' => difficulty($result2['wins'], $result2['losses']),
        ':g_id' => $id[1]
    ]);
} else {
    echo "&success=false";
}
