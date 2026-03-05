<?php
require_once '../../../content/initialize.php';


include('verify.php');
require_once("../../../database/connect.php");

$db = getDatabase();

$username = $_REQUEST['username'];

include(__DIR__ . '/../../../content/checkban.php');
if (!checkBan($username)) {
    header("Location: ../index.php?err=User not banned");
    die();
}

if (
    $db->execute("DELETE FROM banned_members WHERE username=:username", [
      ':username' => $username,
    ])
) {
    include('log.php');
    logModeration('unbanned', $username, 1);
    header("Location: ../index.php?msg=User unbanned successfully");
} else {
    header("Location: ../index.php?err=There was an error while unbanning the user");
}
