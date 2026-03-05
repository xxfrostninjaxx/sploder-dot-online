<?php

// Define the br2nl function
function br2nl($string)
{
    if ($string === null) {
        return null;
    }
    return preg_replace('/<br\s*\/?>/i', "\n", $string);
}

// Get required data...
session_start();
require_once('../content/getgameid.php');
require_once('../database/connect.php');
$db = getDatabase();
$id = get_game_id($_GET['s'])['id'];
$qs = "SELECT author,title,description,g_id,user_id,g_swf,ispublished,isprivate FROM games WHERE g_id = :id";
$game = $db->queryFirst($qs, [':id' => $id]);
if ($_SESSION['username'] != $game['author']) {
header('Location: /?s=' . $_GET['s']);
}
$qs = "SELECT tag FROM game_tags WHERE g_id = :id";
$tags = $db->query($qs, [':id' => $id]);

require('../content/playgame.php');
$game = get_game_info($id);
$status = "playing";
$game['g_swf_version'] = to_creator_type($game['g_swf'])->swf_version();