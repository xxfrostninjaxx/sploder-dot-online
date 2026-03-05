<?php
require_once '../content/initialize.php';

header('Content-Type: text/xml');
session_id($_GET['PHPSESSID']);
session_start();
$data = file_get_contents("php://input");
$id = (int)filter_var(substr($_GET['projid'], 4), FILTER_SANITIZE_NUMBER_INT);
require_once('../repositories/repositorymanager.php');
$gameRepository = RepositoryManager::get()->getGameRepository();


if (!$gameRepository->verifyOwnership($id, $_SESSION['username'])) {
    http_response_code(403);
    die('<message result="failed" message="You do not own this game!"/>');
}
$size = $_GET['size'];
$image_path = "../users/user" . $_SESSION['userid'] . "/images/proj" . $id . "/";

if (!@imagecreatefromstring($data)) {
    http_response_code(403);
    echo '<message result="error" message="Invalid image data"/>';
    exit();
}

if ($size == "small") {
    file_put_contents($image_path . "thumbnail.png", $data);
} else {
    file_put_contents($image_path . "image.png", $data);
}
echo '<message result="success"/>';