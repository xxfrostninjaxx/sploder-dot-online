<?php
require_once '../content/initialize.php';

require_once("../database/connect.php");

$originalMembersDb = getOriginalMembersDatabase();

$u = mb_strtolower($_GET['u']);

require_once("../content/censor.php");
$censoredUsername = censorText($u);

$result2 = $originalMembersDb->query("SELECT username FROM members WHERE username=:user LIMIT 1", [
    ':user' => mb_strtolower($u)
]);

$db = getDatabase();
$result3 = $db->query("SELECT username FROM members WHERE username=:user LIMIT 1", [
    ':user' => $u
]);
if (isset($result3[0]['username'])) {
    $status1 = "cant";
} else if ($censoredUsername !== $u) {
    $status1 = "cant";
} else {
    $status1 = "can";
}

$disallowedUsernames = explode(',', getenv('DISALLOWED_USERNAMES') ?: '');

if (in_array($u, $disallowedUsernames)) {
    $status1 = "cant";
}

if ($status1 == "can") {
    if (isset($result2[0]['username'])) {
        $status = "alert";
    } else {
        $status = "green";
    }
    if ($status == "alert") {
        $status = file_get_contents('../images/alert.png');
    } elseif ($status == "green") {
        $length = strlen($u);
        if ((2 < $length) && ($length < 17) && (ctype_alnum($u))) {
            $status = file_get_contents('../images/check.png');
        } else {
            $status = file_get_contents('../images/ex.png');
        }
    }
} else {
    $status = file_get_contents('../images/ex.png');
}
echo $status;
