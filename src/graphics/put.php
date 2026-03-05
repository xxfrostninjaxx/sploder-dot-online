<?php
require_once '../content/initialize.php';

session_start();
header('Content-Type: text/xml');
$rawdata = file_get_contents("php://input");
$id = $_REQUEST['projid'];
$type = $_GET['objtype'];
$username = $_SESSION["username"];
$userid = $_SESSION["userid"];
require_once('../database/connect.php');
$db = getDatabase();

if ($id == "0") {
    $qs = "INSERT INTO graphics (version, userid, isprivate, ispublished) VALUES (0, :userid, true, false) RETURNING id";
    $id = $db->queryFirstColumn($qs, 0, [
        ':userid' => $userid
    ]);
    $result = $userid;
} else {
    // Check whether the user owns the graphic
    $qs = "SELECT userid FROM graphics WHERE id=:id";
    $result = $db->queryFirstColumn($qs, 0, [
        ':id' => $id
    ]);
}
if ($result == $userid) {
    if ($type == "thumbnail") {
        // Check image dimensions if it is 80px by 80px or 60px by 60px to see if they are valid
        $image = imagecreatefromstring($rawdata);
        $width = imagesx($image);
        $height = imagesy($image);
        if (!($width == 80 && $height == 80) && !($width == 60 && $height == 60)) {
            // Remove the graphic from the database if it is not 80px by 80px
            $qs = "DELETE FROM graphics WHERE id=:id";
            $db->execute($qs, [
                ':id' => $id
            ]);
            die('<message result="error" message="Invalid dimensions! Please note that inappropriate graphics and graphics not made by the creator are strictly forbidden."/>');
        }
        // If 60x60, resize to 80x80
        if ($width == 60 && $height == 60) {
            $image = new Imagick();
            $image->readImageBlob($rawdata);

            // Resize the image to 80x80 (preserving aspect ratio)
            $image->resizeImage(80, 80, Imagick::FILTER_LANCZOS, 1);

            // Set the background to black (no transparency)
            $image->setImageBackgroundColor('black');
            $image = $image->mergeImageLayers(Imagick::LAYERMETHOD_FLATTEN); // Flatten the image to remove transparency

            $image->setImageType(Imagick::IMGTYPE_PALETTE);
            $image->setImageColorspace(Imagick::COLORSPACE_RGB);
            $image->setImageFormat('gif');

            $rawdata = $image->getImageBlob(); // Get the image binary data

            $image->clear();
            $image->destroy();
        }

        file_put_contents("gif/" . $id . ".gif", $rawdata);
    } elseif ($type == "sprite") {
        $isprivate = $_GET['isprivate'] == "1" ? 'true' : 'false';
        $qs = "UPDATE graphics SET ispublished=true, isprivate=:isprivate, version=version+1 WHERE id=:id RETURNING version";
        $version = $db->queryFirstColumn($qs, 0, [
            ':isprivate' => $isprivate,
            ':id' => $id
        ]);
        file_put_contents("png/" . $id . "_" . $version . ".png", $rawdata);
    } elseif ($type == "project") {
        file_put_contents("prj/" . $id . ".prj", $rawdata);
    }
} else {
    die('<message result="error" message="You do not own this graphic"/>');
}



echo '<message result="success" id="' . $id . '" />';