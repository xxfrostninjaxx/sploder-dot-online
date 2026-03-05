<?php
require_once '../content/initialize.php';

// Check whether user owns the graphic
require_once(__DIR__ . '/../repositories/repositorymanager.php');


function verifyIfGraphicOwner(int $graphicId, string $userId): bool
{
    if ($graphicId == 0) {
        return false;
    }
    $graphicsRepository = RepositoryManager::get()->getGraphicsRepository();
    if ($graphicsRepository->getUserId($graphicId) != $userId) {
        return false;
    } else {
        return true;
    }
}
