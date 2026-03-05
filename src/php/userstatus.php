<?php
require_once '../content/initialize.php';

include('../database/connect.php');
$db = getDatabase();

$time = time();
$last = $time - 120;
$pagechange = $time - 900;

$result3 = $db->query("SELECT lastlogin, lastpagechange, status
    FROM members 
    WHERE username=:username", [
        ':username' => $_GET['u']
    ]);

if ($result3[0]['lastlogin'] < $last) {
    $status = "offline";
} elseif ($result3[0]['status'] == "online") {
    if ($result3[0]['lastpagechange'] > $pagechange) {
        $status = "online";
    } else {
        $status = "idle";
    }
} elseif ($result3[0]['status'] == "creating") {
    $status = "making";
} elseif ($result3[0]['status'] == "playing") {
    $status = "playing";
} else {
    $status = "offline";
}
echo file_get_contents('../images/profile_status_' . $status . '.gif');
