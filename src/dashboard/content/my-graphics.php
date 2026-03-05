<?php

function get_total_graphics(mixed $db, int $userid): int
{
    $qs = "SELECT COUNT(id) FROM graphics WHERE userid=:userid";
    $total_graphics = $db->queryFirstColumn($qs, 0, [':userid' => $userid]);
    return $total_graphics;
}

$username = $_SESSION['username'];
$userid = $_SESSION['userid'];
include('../database/connect.php');
$db = getDatabase();
$total_games = get_total_graphics($db, $userid);

$o = isset($_GET['o']) ? $_GET['o'] : "0";
$offset = 12;

$queryString = 'SELECT g.*, COALESCE(l.likes, 0) AS likes
    FROM graphics g
    LEFT JOIN (
        SELECT g_id, COUNT(*) AS likes
        FROM graphic_likes
        GROUP BY g_id
    ) l ON g.id = l.g_id
    WHERE g.userid=:userid
    ORDER BY g.id DESC
    LIMIT 12 OFFSET ' . $o*12;
$result = $db->query($queryString, [':userid' => $userid]);
$total = $total_games;
