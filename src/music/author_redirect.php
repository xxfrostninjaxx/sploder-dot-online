<?php
require_once '../content/initialize.php';
$authors = [
    'moonove' => 'https://moonove.com/',
    'syphus' => 'https://echolevel.co.uk/',
    'soundburst' => 'https://www.skytopia.com/soundburst/modules/mod.html',
    'vim' => 'https://soundcloud.com/vim/',
    'doncato' => 'https://web.archive.org/web/20121227013931if_/http://www.skarv.net/werner/',
    'lpa' => 'https://www.vanille-media.de/code/amiga/'
];

// Get the author parameter from the URL
$author = $_GET['author'] ?? null;

$author = strtolower(trim($author));

// Check if the author exists in our array
if (!array_key_exists($author, $authors)) {
    header('Location: /');
    exit();
}

header('Location: ' . $authors[$author]);