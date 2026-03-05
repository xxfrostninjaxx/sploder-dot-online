<?php
// Make sure the message has only characters available on a standard keyboard
function filterKeyboard($str, $removeNewline = true) {
    // Allow only tab, newline, carriage return, and printable ASCII (space to ~). Remove all Unicode (including emojis).
    if ($removeNewline) {
        return preg_replace("~[^a-zA-Z0-9_ !@#$%^&*();\\\/|<>\"'\+.,:?=\[\]-]~", '', $str);
    } else {
        // Allow \n and \r
        return preg_replace("~[^a-zA-Z0-9_ !@#$%^&*();\\\/|<>\"'\+.,:?=\[\]\r\n-]~", '', $str);
    }
}