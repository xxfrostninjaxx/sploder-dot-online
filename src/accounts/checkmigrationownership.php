<?php
require_once '../content/initialize.php';

# Including all the required scripts for demo
require(__DIR__ . "/discord.php");
require(__DIR__ . "/functions.php");
require(__DIR__ . "/config.php");

# Initializing all the required values for the script to work
init($redirect_url, $client_id, $secret_id, $bot_token);

# Fetching user details | (identify scope) (optionally email scope too if you want user's email) [Add identify AND email scope for the email!]
get_user();

# Uncomment this for using it WITH email scope and comment line 32.
#get_user($email=True);

# Fetching user connections | (connections scope)
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: register.php?err=can');
    die();
}
session_regenerate_id();

if (!isset($_GET["err"])) {
    require_once(__DIR__ . "/../database/connect.php");
    $originalMembersDb = getOriginalMembersDatabase();
    $result2 = $originalMembersDb->query("SELECT username FROM members WHERE userid=:userid", [
        ':userid' => $_SESSION['user_id']
    ]);
    if (isset($result2[0])) {
        if ($_SESSION['enteredusername'] == $result2[0]['username']) {
            $_SESSION['usermigrate'] = "true";
            header('Location: registerpassword.php');
        } else {
            header('Location: register.php?err=dis');
        }
    }
}
