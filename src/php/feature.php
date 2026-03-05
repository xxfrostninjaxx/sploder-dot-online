<?php
require_once '../content/initialize.php';
session_start();
// Login check
if (!isset($_SESSION['loggedin'])) {
    die("Not logged in");
}
require_once('../repositories/repositorymanager.php');
$gameRepository = RepositoryManager::get()->getGameRepository();
$userRepository = RepositoryManager::get()->getUserRepository();

// Check whether the user is actually an editor

$username = $_SESSION['username'];
$perms = $userRepository->getUserPerms($username);
if ($perms === null || $perms === '' || !str_contains($perms, 'E')) {
    die("Haxxor detected");
}

if (!isset($_GET['g_id'])) {
    die("No game ID specified");
}

// Now that we've asserted the user is an editor, we can proceed

$id = (int)filter_var($_GET['g_id'], FILTER_SANITIZE_NUMBER_INT);
$feature = true;
if (isset($_GET['feature']) && $_GET['feature'] === 'false') {
    $feature = false;
}
$gameRepository->setFeaturedStatus($id, $feature, $_SESSION['userid']);
$gameInfo = $gameRepository->getGameBasicInfo($id);
require_once('../games/moderation/php/log.php');
if ($feature) {
    logModeration('featured', $gameInfo['title'] . ' by ' . $gameInfo['author'], 2);
} else {
    logModeration('unfeatured', $gameInfo['title'] . ' by ' . $gameInfo['author'], 2);
}
echo "Success";