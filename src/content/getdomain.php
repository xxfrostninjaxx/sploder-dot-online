<?php
function getDomainNameWithoutProtocolWww(): string {
    $domain = getenv('DOMAIN_NAME');

    $domain = str_replace(['http://', 'https://', 'www.'], '', $domain);
    $domain = rtrim($domain, '/');
    return $domain;
}