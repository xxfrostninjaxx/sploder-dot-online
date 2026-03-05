<?php
require_once '../content/initialize.php';

header("Content-type: text/xml");
$version = $_GET['version'] ?? 1;
if((($_GET['PHPSESSID'] ?? null) == 'demo' || !isset($_GET['PHPSESSID'])) && $version == 7){
    showArcadeDemoGames();
    exit;
}
if (isset($_GET['PHPSESSID'])) {
    session_id($_GET['PHPSESSID']);
}
session_start();
if (isset($_SESSION['PHPSESSID'])) { // session ID is valid and exists
    $author = $_SESSION["username"];
    $num = $_GET['num'] ?? 10;
    $start = $_GET['start'] ?? 0;
    if(in_array(
        $version,
        ["5", "7"] // Turns out that literally only the Physics and Arcade support dynamically loading the games lst
    )){
        $newFormat = true;
    } else {
        $newFormat = false;
    }
    require_once('../database/connect.php');
    $db = getDatabase();
    $queryString = 'SELECT * FROM games WHERE author = :author AND g_swf = :g_swf AND isdeleted = :isdeleted ORDER BY g_id DESC';
    $params = [
        ':g_swf' => $version,
        ':author' => $author,
        ':isdeleted' => '0'
    ];

    if ($newFormat) {
        $queryString .= " LIMIT :num OFFSET :start";
        $params[':start'] = $start;
        $params[':num'] = $num;
    }

    $result = $db->query($queryString, $params);
    //print_r($result);
    $resultTotal = count($result);
    $totalGames = $db->queryFirstColumn("SELECT COUNT(g_id)
        FROM games
        WHERE author= :author
        AND g_swf = :g_swf
        AND isdeleted = :isdeleted", 0, [
        ':g_swf' => $version,
        ':author' => $author,
        ':isdeleted' => '0'
        ]);

    if ($newFormat) {
        $string = '<projects total="' . $totalGames . '" start="' . $start . '" num="' . $num . '">';
    } else {
        $num = $resultTotal;
        $string = '<projects total="' . $totalGames . '">';
    }

    foreach ($result as $project) {
        $string .= '<project id="proj' . $project['g_id'] . '" src="proj' . $project['g_id'] . '.xml" title="' . $project['title'] . '" date="' . date("l, F jS, Y", strtotime($project['date'])) . '" time="' . strtotime($project['date']) . '" archived="0" />';
    }
    $string .= '</projects>';
    print($string);
} else {
    if (($_GET['PHPSESSID'] ?? null) != "demo") {
        echo '<projects total="1" start="0" num="10"><project id="notset" src="notset" title="The session ID is incorrect!" date="Log out and log in again." archived="0"/></projects>';
    } else {
        echo '<projects total="1" start="0" num="10"><project id="notset" src="notset" title="The creator is in demo mode!" date="Loading is disabled." archived="0"/></projects>';
    }
}

function showArcadeDemoGames():void {
    echo '<projects total="1" start="0" num="10"><project id="notset" src="notset" title="No demo games for now, sad face" time="'.time().'" archived="0"/></projects>';
}