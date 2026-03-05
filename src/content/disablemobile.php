<?php
// Check if the visitor is on a mobile device
// Credits to whoever I stole this from... I honestly forgot
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
if (!preg_match("/(bot|spider|crawler|slurp)/i", $user_agent) &&
    preg_match("/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i", $user_agent)) {  
    header('Location: /error_pages/mob.php');
    exit();
}
