<?php
require_once '../content/initialize.php';

header('Content-Type: application/xml');
session_start();
$start = $_GET['start'] ?? 0; 
$num = $_GET['num'] ?? 12; 

require('../database/connect.php');

$db = getDatabase();

$clause = "ispublished=true AND isprivate=false";
$order = "likes";
$extrainfo = "";
$params = [
    ':num' => $num,
    ':start' => $start
];


$isLoggedIn = isset($_SESSION['userid']) && $_GET['userid'] == $_SESSION['userid'];


if ($isLoggedIn && !isset($_GET['searchmode'])) {
    $clause = "1=1";
    $params[':userid'] = $_SESSION['userid'];
    $clause .= " AND userid=:userid";
    $order = "id";
} elseif ($_GET['userid'] == 0 || isset($_GET['searchmode'])) {
    if (isset($_GET['searchmode'])) {
        $searchmode = $_GET['searchmode'];
        $searchterm = $_GET['searchterm'];
        if ($searchmode == "users") {
            $order = "id";  
            $qs = "SELECT userid FROM members WHERE username=:username";
            $userid = $db->queryFirstColumn($qs, 0, [':username' => $searchterm]);
            if ($userid) {
                
                $clause .= " AND userid=:userid";
                $params[':userid'] = $userid;
            } else {
                
                $clause .= " AND 1=0";
            }
        } else {
            $qs = "SELECT g_id FROM graphic_tags WHERE SIMILARITY(tag, :tag) > 0.3";
            $rows = $db->query($qs, [':tag' => $searchterm]);
            $g_ids = array_column($rows, 'g_id');
            if (!empty($g_ids)) {
                $g_ids = array_unique($g_ids); 
                $clause .= " AND id IN (" . implode(",", array_map('intval', $g_ids)) . ")";
            } else {  
                $clause .= " AND 1=0";
            }
        }
    } else {
        $extrainfo .= ", (SELECT username FROM members WHERE userid=graphics.userid) AS username";
    }
    $extrainfo .= ", (SELECT COUNT(*) FROM graphic_likes WHERE graphic_likes.g_id=graphics.id) AS likes"; 
    if ($isLoggedIn && isset($_GET['searchmode'])) {
        $clause = "userid=:userid AND " . $clause;
        $params[':userid'] = $_SESSION['userid'];
        $clause = str_replace("ispublished=true AND isprivate=false", "1=1", $clause);
    }
}


$graphics_qs = "SELECT id, version$extrainfo FROM graphics WHERE $clause ORDER BY $order DESC LIMIT :num OFFSET :start";
$graphicsData = $db->query($graphics_qs, $params);
$graphicsData = array_map(function($graphic) {
    $filteredGraphic = array_filter($graphic, function($key) {
        return !is_numeric($key) && $key !== 'likes';
    }, ARRAY_FILTER_USE_KEY);
    return $filteredGraphic;
}, $graphicsData);
unset($params[':num']);
unset($params[':start']);
$total = $db->queryFirstColumn("SELECT COUNT(*) FROM graphics WHERE $clause", 0, $params);

$xml = new SimpleXMLElement('<graphics/>');
$xml->addAttribute('start', $start);
$xml->addAttribute('num', $num);
$xml->addAttribute('total', $total);

foreach ($graphicsData as $graphic) {
    $g = $xml->addChild('g');
    foreach ($graphic as $key => $value) {
        $g->addAttribute($key, $value);
    }
}

echo $xml->asXML();