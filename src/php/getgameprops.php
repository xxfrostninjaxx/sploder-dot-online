<?php
require_once '../content/initialize.php';

$separated = explode("_", $_GET['pubkey']);
// Check whether pubkey matches game ID ad User ID
require_once('../database/connect.php');
$db = getDatabase();
$qs = "SELECT user_id, g_swf FROM games WHERE g_id = :id";
$game = $db->queryFirst($qs, [':id' => $separated[1]]);
if($separated[0] == $game['user_id']){
    if($game['g_swf'] == 7) {
        session_start();
        require_once(__DIR__ . '/../repositories/repositorymanager.php');
        require_once(__DIR__ . "/../accounts/getip.php");
        $gameRepository = RepositoryManager::get()->getGameRepository();
        $gameRepository->trackView($separated[1], getVisitorIp(), $_SESSION['userid'] ?? null);
    }
?>
&u=<?= $separated[0] ?>&c=0&m=<?= $separated[1] ?>&tv=0&a=0
<?php
} else {
    echo 'Error';
    die();
}