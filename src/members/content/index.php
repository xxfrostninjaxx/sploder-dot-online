<?php

session_start();
include('../database/connect.php');
$username = $_GET['u'];

require_once('../content/timeelapsed.php');

$db = getDatabase();
$userParam = [':username' => $username];
$publicgames = " AND isdeleted=0 AND ispublished=1 AND isprivate=0";

// Fetch total and featured games
$games = $db->query("
WITH UserGameCount AS (
    SELECT COUNT(g_id) as total_user_games
    FROM games
    WHERE author = :username
        AND ispublished = 1
        AND isprivate = 0
        AND isdeleted = 0
),
FeaturedGameCount AS (
    SELECT COUNT(G.g_id) as total_featured_games
    FROM featured_games AS FG
    JOIN games AS G ON FG.g_id = G.g_id
    WHERE G.author = :username
        AND G.ispublished = 1
        AND G.isprivate = 0
        AND G.isdeleted = 0
)
SELECT
    (SELECT total_user_games FROM UserGameCount) AS total_game_count,
    (SELECT total_featured_games FROM FeaturedGameCount) AS featured_game_count;
", $userParam);
$totalgames = $games[0]['total_game_count'] ?? 0;
$featuredgames = $games[0]['featured_game_count'] ?? 0;

// Fetch user details
$result = $db->queryFirst("SELECT userid, perms, joindate, lastlogin FROM members WHERE username = :username", $userParam);
$exists = isset($result['userid']) ? 1 : 0;
$user_id = $exists ? $result['userid'] : null;

// Fetch user level
require_once('../repositories/repositorymanager.php');

$userRepository = RepositoryManager::get()->getUserRepository();
$result['level'] = $userRepository->getLevelByUserId($user_id);

// Fetch total plays
$plays = $db->queryFirstColumn("SELECT COUNT(1) FROM leaderboard WHERE pubkey IN (SELECT g_id FROM games WHERE author = :username $publicgames)", 0, $userParam);

// Fetch total playtime
$playtime = gmdate("i:s", round($db->queryFirstColumn("SELECT SUM(gtm) FROM leaderboard WHERE pubkey IN (SELECT g_id FROM games WHERE author = :username $publicgames)", 0, $userParam) / max(1, $plays)));

// Fetch total votes
$votes = $db->queryFirstColumn("SELECT COUNT(1) FROM votes WHERE g_id IN (SELECT g_id FROM games WHERE author = :username $publicgames)", 0, $userParam);

//Get feedback in percentage by calculating average vote of games (0-5)
if ($result['lastlogin'] > (time() - 30)) {
    $result['lastlogin'] = time();
}
$total = $totalgames;
