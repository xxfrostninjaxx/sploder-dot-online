<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require('content/verify.php');
require('../content/censor.php');
$description = trim(censorText($_POST['description']));

// Enforce max length of 2000 characters
if (strlen($description) > 2000) {
    $description = substr($description, 0, 2000);
}

// Check whether the description contains characters other than alphabets, numbers, spaces and !@#$%^&*()_+{}|:"<>?`-=[]\;',./
if (!preg_match('/^[a-zA-Z0-9 !@#$%^&*()_+{}|:"<>?`\-=\[\]\\;\',.\/\n\r]*$/', $description)) {
    // Send 400
    http_response_code(400);
}

// Check whether the description is not just spaces and remove starting and trailing spaces
if ($description == '') {
    // Send 400
    $description = null;
} else {
    // Compress multiple newlines into maximum two newlines
    $description = preg_replace('/[\r\n]{2,}/', "\n\n", $description);
}

// Update description in database
$qs = "UPDATE games SET description = :description WHERE g_id = :id";
$db->execute($qs, [':description' => $description, ':id' => $id]);