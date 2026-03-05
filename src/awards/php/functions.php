<?php
require_once(__DIR__ . "/../../database/connect.php");

function isEditor()
{
    $db = getDatabase();
    $qs = "SELECT perms FROM members WHERE username = :username";
    $permissions = $db->queryFirstColumn($qs, 0, [
      ':username' => $_SESSION['username'],
    ]);
    if ($permissions === null || $permissions == "") {
        return false;
    }
    return str_contains($permissions, "E");
}
function getMaxCustomization($level, $isEditor)
{


    $maxCustomization = 0;
    // If level is 10 or more, set maxCustomization to 1
    // If level is 25 or more, set maxCustomization to 3
    // If level is 50 or more, set maxCustomization to 6
    // If user is an editor, set maxCustomization to 7
    if ($level >= 50) {
        $maxCustomization = 6;
    } elseif ($level >= 25) {
        $maxCustomization = 3;
    } elseif ($level >= 10) {
        $maxCustomization = 1;
    }
    if ($isEditor) {
        $maxCustomization = 7;
    }
    return "[" . $maxCustomization . "," . $maxCustomization . "," . $maxCustomization . "," . $maxCustomization . "," . $maxCustomization . "," . $maxCustomization . "]";
}

function reduceAward()
{
    $db = getDatabase();
    $creationdate = time();
    return $db->execute("INSERT INTO awards_sent
        (username, creationdate)
        VALUES (:username, :creationdate)", [
            ':username' => $_SESSION['username'],
            ':creationdate' => $creationdate]);
}

function maxAward($level)
{
    $db = getDatabase();
    // Get total number of awards sent in a day
    $maxAwards = $db->queryFirstColumn("SELECT COUNT(*)
        FROM awards_sent
        WHERE username = :username
        AND creationdate > :creationdate", 0, [
            ':username' => $_SESSION['username'],
            ':creationdate' => time() - 86400]);

    // If null, change to 0
    if ($maxAwards == null) {
        $maxAwards = 0;
    }
    return floor($level / 10) - $maxAwards;
}
