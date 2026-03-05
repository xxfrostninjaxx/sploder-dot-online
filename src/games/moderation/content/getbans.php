<?php

require_once(__DIR__."/../../../database/connect.php");

$db = getDatabase();

$bans = $db->query("SELECT username, reason, banned_by, bandate, autounbandate
    FROM banned_members
    WHERE autounbandate>:autounbandate
    ORDER BY bandate DESC", [
  ':autounbandate' => time()
]);
