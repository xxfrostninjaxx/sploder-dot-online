<?php
function get_game_id($s): array
{
    $s_array = explode("_", $s);
    $game_id['userid'] = $s_array[0];
    $game_id['id'] = end($s_array);
    return $game_id;
}