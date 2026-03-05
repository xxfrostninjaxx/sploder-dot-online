<?php
require_once '../content/initialize.php';

session_start();
session_destroy();
session_start();

require_once("../database/connect.php");

$u = mb_strtolower($_POST['username']);

require_once("../content/censor.php");
$censoredUsername = censorText($u);

if ($censoredUsername !== $u) {
    header('Location: register.php?err=cens');
    exit();
}

$originalMembersDb = getOriginalMembersDatabase();
#$qs = "UPDATE sploder SET isprivate=" . $isprivate . " WHERE g_id = " . $id;
$result2 = $originalMembersDb->query("SELECT username FROM members WHERE username=:user LIMIT 1", [
    ':user' => $u
]);

$db = getDatabase();
$result3 = $db->query("SELECT username FROM members WHERE username=:user LIMIT 1", [
    ':user' => $u
]);

if (isset($result3[0]['username'])) {
    $status1 = "cant";
} else {
    $status1 = "can";
}

$disallowedUsernames = explode(',', getenv('DISALLOWED_USERNAMES') ?: '');

if (in_array($u, $disallowedUsernames)) {
    header('Location: register.php?err=cens');
    exit();
}

if ($status1 == "can") {
    if (isset($result2[0]['username'])) {
        $status = "alert";
    } else {
        $status = "green";
    }
    if ($status == "alert") {
        require(__DIR__ . "/functions.php");
        require(__DIR__ . "/discord.php");
        require(__DIR__ . "/config.php");
        $auth_url = url($client_id, $redirect_url, $scopes);
        $_SESSION['enteredusername'] = $u;
        header('Location: ' . $auth_url);
    } elseif ($status == "green") {
        $length = strlen($u);
        if ((2 < $length) && ($length < 17) && (ctype_alnum($u))) {
            header('Location: registerpassword.php');
            $_SESSION['usermigrate'] = "false";
            $_SESSION['enteredusername'] = $u;
        } else {
            header('Location: register.php?err=inv');
        }
    }
} else {
    header('Location: register.php?err=acc');
}
