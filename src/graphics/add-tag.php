<?php
require_once '../content/initialize.php';

include('../content/logincheck.php');
require_once('verify.php');
require_once('../content/censor.php');
require_once(__DIR__ . '/../repositories/repositorymanager.php');

$id = (int)$_POST['id'];
$verified = verifyIfGraphicOwner((int)$id, $_SESSION['userid']);

function validationError(int $id, string $error)
{
    header('Location: ../dashboard/tag-graphic.php?id=' . $id . '&err=' . urlencode($error));
    die();
}

if (!$verified) {
    validationError($id, "You are not the owner of this graphic");
}

$tags = explode(" ", censorText($_POST['tags']));
// Check whether each tag is valid
// For a tag to be valid, it must be less than 30 characters long
// It also must have only letters and numbers
$newTags = [];
foreach ($tags as $tag) {
    // If a tag is empty, remove it from the array
    if ($tag == '' || $tag == 'splode') {
        continue;
    }
    // Change tag to lowercase
    $tag = strtolower($tag);
    if (strlen($tag) > 30) {
        validationError($id, "Tags must be less than 30 characters long.");
    }
    if (!preg_match('/^[a-zA-Z0-9]*$/', $tag)) {
        validationError($id, "Tags can only contain letters and numbers. Use spaces to separate tags.");
    }
    $newTags[] = $tag;
}
$tags = $newTags;
// There must not be more than 25 tags
if (count($tags) > 25) {
    validationError($id, "You can only have up to 25 tags.");
}
// There cannot be 2 tags with the same name
if (count($tags) != count(array_unique($tags))) {
    validationError($id, "You cannot have 2 tags with the same name.");
}

// Update tags in database
$graphicsRepository = RepositoryManager::get()->getGraphicsRepository();
$graphicsRepository->replaceTags($id, $tags);
header('Location: ../dashboard/tag-graphic.php?id=' . $id . '&success=true');
