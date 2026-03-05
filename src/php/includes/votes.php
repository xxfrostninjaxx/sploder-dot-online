<?php
include_once('../database/connect.php');
function get_votes($g_id)
{
    $db = getDatabase();
    return $db->queryFirst("SELECT AVG(score) as avg, COUNT(*) as count FROM votes WHERE g_id = :g_id", [
        ':g_id' => $g_id
    ]);
}
function vote_check($username, $g_id): bool
{
    $db = getDatabase();
    $result = $db->query("SELECT * FROM votes WHERE username = :username AND g_id = :g_id", [
        ':username' => $username,
        ':g_id' => $g_id
    ]);
    if (count($result) > 0) {
        return true;
    } else {
        return false;
    }
}
