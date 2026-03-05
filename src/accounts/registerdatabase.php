<?php
require_once '../content/initialize.php';

include('getip.php');
function error_found()
{
    header("Location: register.php?err=unk");
}
// set_error_handler('error_found');
$captcha = $_POST['cf-turnstile-response'];

if (!$captcha) {
    // What happens when the CAPTCHA was entered incorrectly
    header('Location: register.php?err=cap');
    exit;
}

$ip = getVisitorIp();
require_once('../config/env.php');
$secretKey = getenv("CF_TURNSTILE_SECRET_KEY");

$url_path = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
$data = array('secret' => $secretKey, 'response' => $captcha, 'remoteip' => $ip);

$options = array(
    'http' => array(
        'header' => "Content-Type: application/x-www-form-urlencoded",
        'method' => 'POST',
        'content' => http_build_query($data)
    )
);

$stream = stream_context_create($options);

$result = file_get_contents(
    $url_path,
    false,
    $stream
);

$response =  $result;

$responseKeys = json_decode($response, true);

if (intval($responseKeys["success"]) !== 1) {
    header('Location: register.php?err=cap');
} else {
    session_start();
    $password = $_POST['pass2'];
    if (strlen($password) > 25) {
        $password = substr($password, 0, 25);
    }
    $isolate = $_POST['social'] ?? "off";
    $tostest = $_POST['tostest'] ?? "off";
    $username = mb_strtolower($_SESSION['enteredusername']);
    require_once("../content/censor.php");
    $censoredUsername = censorText($username);

    if ($censoredUsername !== $username) {
        header('Location: register.php?err=cens');
        exit();
    }
    $hashed = password_hash($password, PASSWORD_BCRYPT);
    if ($isolate == "on") {
        $isolate = "0";
    } else {
        $isolate = "1";
    }
    $t = time();
    if ($tostest == "on") {
        require('../database/connect.php');

        $originalMembersDb = getOriginalMembersDatabase();
        $result2 = $originalMembersDb->query("SELECT username FROM members WHERE username=:user LIMIT 1", [
            ':user' => $username
        ]);

        $db = getDatabase();
        $result3 = $db->query("SELECT username FROM members WHERE username=:user LIMIT 1", [
            ':user' => $username
        ]);

        if (isset($result3[0]['username'])) {
            $status1 = "cant";
        } else {
            $status1 = "can";
        }

        $disallowedUsernames = explode(',', getenv('DISALLOWED_USERNAMES') ?: '');

        if (in_array($username, $disallowedUsernames)) {
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
                if ($_SESSION['enteredusername'] == $result2[0]['username']) {
                    $qs = "INSERT INTO members (username, password, joindate, lastlogin, isolate, boostpoints, lastpagechange, ip_address) VALUES (:username, :password, :join, :lastlogin, :isolate, :boostpoints, :lastpagechange, :ip_address)";
                    $db->execute($qs, [
                        ':username' => $username,
                        ':password' => $hashed,
                        ':join' => $t,
                        ':lastlogin' => $t,
                        ':isolate' => $isolate,
                        ':boostpoints' => '250',
                        ':lastpagechange' => '0',
                        ':ip_address' => $ip
                    ]);
                    session_destroy();
                    header('Location: registersuccess.php');
                }
            } elseif ($status == "green") {
                $length = strlen($username);
                if ((2 < $length) && ($length < 17)) {
                    $qs = "INSERT INTO members (username, password, joindate, lastlogin, isolate, boostpoints, lastpagechange, ip_address) VALUES (:username, :password, :join, :lastlogin, :isolate, :boostpoints, :lastpagechange, :ip_address)";
                    $db->execute($qs, [
                        ':username' => $username,
                        ':password' => $hashed,
                        ':join' => $t,
                        ':lastlogin' => $t,
                        ':isolate' => $isolate,
                        ':boostpoints' => '250',
                        ':lastpagechange' => '0',
                        ':ip_address' => $ip
                    ]);
                    session_destroy();
                    header('Location: registersuccess.php');
                } else {
                }
            }
        } else {
        }
    }
}
