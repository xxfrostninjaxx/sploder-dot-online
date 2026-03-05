<?php
require_once '../content/initialize.php';
header('Content-Type: image/png');
$username = $_GET['u'];
// Check file exists php
if (file_exists('../avatar/a/' . $username . '.png')) {
    $raw = file_get_contents('../avatar/a/' . $username . '.png');
} else {
    $raw = file_get_contents('../avatar/a/fb/noob.png');
}

// Allow only few sizes
$size = $_GET['size'] ?? 96;
if ($size != 96 && $size != 48 && $size != 24) {
    $size = 96;
}
// Resize the image if its not 96x96
if ($size != 96) {
    // Allow caching for 1 day
    header('Cache-Control: public, max-age=86400');
    $img = imagecreatefromstring($raw);
    $img = imagescale($img, $size, $size);

    // Preserve transparency
    imagesavealpha($img, true);
    $transparent = imagecolorallocatealpha($img, 0, 0, 0, 127);
    imagefill($img, 0, 0, $transparent);
    imagepng($img);
    die();
}
echo $raw;
