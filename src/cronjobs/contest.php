<?php
require_once (__DIR__.'/../content/initialize.php');

echo "Service  Running!\n";
require_once(__DIR__.'/../database/connect.php');

// Hacky! But works without having to load the entire environment in cron
// It never needs to be accessed anyway so it doesn't matter
putenv('ORIGINAL_MEMBERS_DB=stub');
$db = getDatabase();

$day = date("w");
// Day of the week
 echo "Day of the week: " . date("w\-l") . "\n";
 // Save current contest...
if ($day == 1) {
    $file = __DIR__ . '/../config/currentcontest.txt';
// Get current contest
    $current = file_get_contents($file);
    $updated = $current + 1;
    // Save contest data
    file_put_contents($file, $updated);
} elseif ($day == 3) {
    $db->execute("INSERT INTO contest_votes (id)
    SELECT g_id
    FROM contest_nominations
    GROUP BY g_id
    ORDER BY COUNT(*) DESC
    LIMIT 32;");
    $db->execute("DELETE FROM contest_nominations");
} elseif ($day == 6) {
    $db->execute("INSERT INTO contest_winner (g_id, contest_id)
    SELECT id, :contest_id
    FROM contest_votes
    ORDER BY votes DESC
    LIMIT 3;", [
      ':contest_id' => file_get_contents(__DIR__ . '/../config/currentcontest.txt')
    ]);
    $db->execute("DELETE FROM contest_votes");
    $db->execute("DELETE FROM contest_voter_usernames");
}
