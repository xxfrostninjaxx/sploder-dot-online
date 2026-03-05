<?php
require_once '../content/initialize.php';

$url = $_POST["url"];
require_once(__DIR__ . '/../config/env.php');

$envParsed = parse_url(getenv("DOMAIN_NAME"));
$domainName = $envParsed['host'] ?? '';
$back = $_POST["back"] ?? '';
$back = str_replace(["&urlerr=1", "&errload=1"], "", $back);

$parsedUrl = parse_url($url);
if ($parsedUrl === false) {
    $backWithQuery = add_query_param($back, "urlerr", "1");
    header("Location: " . $backWithQuery);
    exit;
}

$host = $parsedUrl['host'] ?? '';
$path = $parsedUrl['path'] ?? '';
$scheme = $parsedUrl['scheme'] ?? '';
$query = $parsedUrl['query'] ?? '';

$normalizedDomainName = str_replace('www.', '', $domainName);
$normalizedHost = str_replace('www.', '', $host);

$isDomainValid = ($normalizedHost === $normalizedDomainName);

$documentRoot = $_SERVER['DOCUMENT_ROOT'];
$filePath = $documentRoot . $path;
$fileExists = file_exists($filePath) || is_dir($filePath);

if (!$isDomainValid) {
    $backWithQuery = add_query_param($back, "urlerr", "1");
    header("Location: " . $backWithQuery);
} elseif (!$fileExists) {
    $backWithQuery = add_query_param($back, "errload", "1");
    header("Location: " . $backWithQuery);
} else {
    $safeUrl = $scheme . '://' . $host . $path;
    if (!empty($query)) {
        $safeUrl .= '?' . $query;
    }
    header("Location: " . $safeUrl);
}

exit;

function add_query_param($url, $param, $value) {
    $url = rtrim($url, '?&');
    $separator = (strpos($url, '?') === false) ? '?' : '&';
    return $url . $separator . $param . '=' . urlencode($value);
}