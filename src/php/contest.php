<?php
require_once '../content/initialize.php';

function is_winner($id)
{
    $db = getDatabase();
    $result = $db->query("SELECT * FROM contest_winner WHERE g_id = :id", [
        ':id' => $id
    ]);
    if (isset($result[0][0])) {
        return true;
    }
    return false;
}
include('../database/connect.php');
session_start();
$output = "";
$a = $_POST['action'];
$day = date("w");
$lastContest = file_get_contents('../config/currentcontest.txt');
if ($day >= 1 && $day <= 5) {
    $lastContest = $lastContest - 1;
}
// Check the referrer and handle games/contest.php differently
if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'games/contest.php') !== false) {
    $db = getDatabase();
    // Basically, we get the number of pages that would correctly fit all the contest winners
    $totalWinners = $db->queryFirstColumn("SELECT COUNT(*) FROM contest_winner");
    // Set lastContest to the number of pages, per page, there are 6 winners
    $lastContest = ceil($totalWinners / 6);
}
// Contest status, 0 = results, 1 = nominations, 2 = voting
if (is_winner($_POST['game_id'] ?? -1)) {
    $output .= '&is_winner=1';
    $output .= '&accepting_entries=0';
    die($output);
}

if ($day == 1 || $day == 2) {
    $status = 1;
} elseif ($day == 3 || $day == 4 || $day == 5) {
    $status = 2;
} else {
    $status = 0;
    $output .= '&accepting_entries=0';
    $output .= '&is_winner=0';
    $output .= '&complete=1';
}
if ($a == "status") {
    if ($status == 0) {
        $status = 0;
        $output .= '&accepting_entries=0';
        $output .= '&voting=0';
    } elseif ($status == 1) {
        $output .= '&accepting_entries=1';
        $id = $_POST['game_id'] ?? -1;
        $db = getDatabase();
        $result = $db->query("SELECT *
            FROM contest_nominations
            WHERE g_id = :id
            AND nominator_username = :username", [
            ':id' => $id,
            ':username' => $_SESSION['username'] ?? ''
        ]);
        if (count($result) > 0) {
            $output .= '&already_nominated=1';
        } else {
            $output .= '&can_nominate=1';
        }
    } elseif ($status == 2) {
        $output .= "&voting=1";
        $id = $_POST['game_id'] ?? -1;
        $username = $_SESSION['username'] ?? '';
        $db = getDatabase();

        $result = $db->query("
            SELECT 
                EXISTS (SELECT 1 FROM contest_votes WHERE id = :id) AS game_exists,
                EXISTS (
                    SELECT 1 FROM contest_voter_usernames 
                    WHERE voter_username = :username 
                    AND id = :id
                ) AS already_voted,
                (SELECT COUNT(*) FROM contest_voter_usernames WHERE voter_username = :username) AS ballot_count
            ",
            [
                ':id' => $id,
                ':username' => $username,
            ]
        );

        if ($result) {
            $row = $result[0];

            if (!$row['game_exists']) {
                $output .= '&can_vote=0';
            } elseif ($row['already_voted']) {
                $output .= '&already_voted=1';
            } elseif ($row['ballot_count'] >= 3) {
                $output .= '&max_ballots_cast=1';
            } else {
                $output .= '&can_vote=1';
            }
        }

    }

    $output .= '&lastContest=' . $lastContest;

    if (isset($_SESSION['username'])) {
        $output .= '&session=1';
    }
    //$output .= '&can_nominate=1';
} elseif ($a == "nominate") {
    if (!isset($_SESSION['username'])) {
        die('&success=false');
    }
    $id = $_POST['game_id'] ?? -1;

    $db = getDatabase();
    $result = $db->query("SELECT * FROM contest_nominations WHERE g_id = :id AND nominator_username = :username", [
        ':id' => $id,
        ':username' => $_SESSION['username'] ?? ''
    ]);

    if (count($result) > 0) {
        die('&success=false');
    } else {
        $db->execute("INSERT INTO contest_nominations (g_id, nominator_username) VALUES (:id, :username)", [
            ':id' => $id,
            ':username' => $_SESSION['username'] ?? ''
        ]);
    }

    $output .= '&success=true';
} elseif ($a == "vote") {
    if (!isset($_SESSION['username'])) {
        die('&success=false');
    }
    $id = $_POST['game_id'] ?? -1;

    $db = getDatabase();
    $result = $db->query("SELECT * FROM contest_votes WHERE id = :id", [
        ':id' => $id,
    ]);

    if (count($result) != 1) {
        die('&success=false');
    }

    $result = $db->query("SELECT * FROM contest_voter_usernames WHERE voter_username = :username", [
        ':username' => $_SESSION['username'] ?? '',
    ]);

    if (count($result) >= 3) {
        die('&success=false');
    }
    if ($result[0][0] == $id || $result[1][0] == $id || $result[2][0] == $id) {
        die("&success=false");
    } else {
        $db->execute("INSERT INTO contest_voter_usernames (id,voter_username) VALUES (:id, :username)", [
            ':id' => $id,
            ':username' => $_SESSION['username'] ?? ''
        ]);
        $db->execute("UPDATE contest_votes SET votes = votes + 1 WHERE id = :id", [
            ':id' => $id
        ]);
    }

    $output .= '&success=true';
}
echo $output;
