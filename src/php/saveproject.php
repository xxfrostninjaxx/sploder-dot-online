<?php
require_once '../content/initialize.php';
require('includes/saveproject.php');
$id = saveProject(1);
require('thumbnails/thumb.php');
$xml = file_get_contents('php://input');
$image_path = "../users/user" . $_SESSION['userid'] . "/images/proj" . $id . "/";
generateImageFromXML($xml, $image_path, true);