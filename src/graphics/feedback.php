<?php
require_once '../content/initialize.php';

include('../content/logincheck.php');

require_once(__DIR__ . '/../repositories/repositorymanager.php');

$graphicsRepository = RepositoryManager::get()->getGraphicsRepository();

$id = $_GET['projid'];
$a = $_GET['action'];

if ($a == "like") {
    $graphicsRepository->trackLike($id, $_SESSION['userid']);
}
