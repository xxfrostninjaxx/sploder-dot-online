<?php
require_once '../content/initialize.php';
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedin'])) {
    die('Your session expired! Please log in again.');
}

require_once(__DIR__.'/../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();

$data = explode("-", $_GET['c']);
$type = $data[0];
$skinColor = $data[1];
$skinStyle = $data[2];
$mouthColor = $data[3];
$mouthStyle = $data[4];
$noseColor = $data[5];
$noseStyle = $data[6];
$eyeColor = $data[7];
$eyeStyle = $data[8];
$hairColor = $data[9];
$hairStyle = $data[10];
$extrasColor = $data[11];
$extrasStyle = $data[12];

if ($type == 'premium') {
    // If the user has a premium avatar for less than 15 minutes, they can edit it for free
    if (isset($_SESSION['premium_avatar']) && time() - $_SESSION['premium_avatar'] < 15*60) {
        // Allow editing for free
    } else {
        $boostPoints = $userRepository->getBoostPoints($_SESSION['userid']);
        if ($boostPoints < 150) {
            die('You do not have enough boost points to create a premium avatar!');
        }
        $userRepository->removeBoostPoints($_SESSION['userid'], 150);
        // Set the premium avatar to be the current time
        $_SESSION['premium_avatar'] = time();
    }
}

$avatarSuffix = $type == 'classic' ? '' : '.1';
$avatarFiles = [
    'layer_01' => "avatar_01_96.png{$avatarSuffix}", // Skin
    'layer_02' => "avatar_02_96.png{$avatarSuffix}", // Mouth
    'layer_03' => "avatar_03_96.png{$avatarSuffix}", // Nose
    'layer_04' => "avatar_04_96.png{$avatarSuffix}", // Eyes
    'layer_05' => "avatar_05_96.png{$avatarSuffix}", // Hair
    'layer_06' => "avatar_06_96.png{$avatarSuffix}", // Extras
    'layer_07' => "avatar_07_96.png{$avatarSuffix}"  // Base/Background
];

$width = 96;
$height = 96;

// Create final image
$finalImage = imagecreatetruecolor($width, $height);
imagesavealpha($finalImage, true);
$transparent = imagecolorallocatealpha($finalImage, 0, 0, 0, 127);
imagefill($finalImage, 0, 0, $transparent);

// Layer data: [color, style] for each layer
$layerData = [
    'layer_01' => [$skinColor, $skinStyle],    // Skin
    'layer_02' => [$mouthColor, $mouthStyle],  // Mouth
    'layer_03' => [$noseColor, $noseStyle],    // Nose
    'layer_04' => [$eyeColor, $eyeStyle],      // Eyes
    'layer_05' => [$hairColor, $hairStyle],    // Hair
    'layer_06' => [$extrasColor, $extrasStyle], // Extras
    'layer_07' => [0, 0]                       // Base (no color/style variations)
];

// Render layers in the correct order (01-06 first, then 07 on top)
$renderOrder = ['layer_01', 'layer_02', 'layer_03', 'layer_04', 'layer_05', 'layer_06', 'layer_07'];

foreach ($renderOrder as $layerName) {
    $sourceImage = imagecreatefrompng($avatarFiles[$layerName]);
    if (!$sourceImage) continue;
    
    list($color, $style) = $layerData[$layerName];
    
    // Calculate source position based on JavaScript logic: -color*96px -style*96px
    // BUT: layer_07 (base) always uses position 0,0 (no background-position applied in JS)
    if ($layerName === 'layer_07') {
        $sourceX = 0;
        $sourceY = 0;
    } else {
        $sourceX = $color * $width;
        $sourceY = $style * $height;
    }
    
    // Copy the specific part of the source image to the final image
    imagecopy(
        $finalImage,     // destination
        $sourceImage,    // source
        0,              // dest_x
        0,              // dest_y
        $sourceX,       // src_x
        $sourceY,       // src_y
        $width,         // width
        $height         // height
    );
    
    imagedestroy($sourceImage);
}

// Save the final image
$success = imagepng($finalImage, 'a/' . $_SESSION["username"] . '.png');
imagedestroy($finalImage);

if ($success) {
    echo "prompt:Avatar saved successfully!";
} else {
    echo "Error saving avatar!";
}
