<?php
function isAdmin($username): bool {
    require_once(__DIR__ . '/../../../repositories/repositorymanager.php');
    $userRepository = RepositoryManager::get()->getUserRepository();
    $perms = $userRepository->getUserPerms($_SESSION['username']);
    if ($perms === null || $perms === '') {
        return false;
    }
    if (str_contains($perms, 'A')) {
        return true;
    }
    return false;
}