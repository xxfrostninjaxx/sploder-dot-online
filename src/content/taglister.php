<?php

enum TagType: string
{
    case Games = 'games';
    case Graphics = 'graphics';
}

/**
 * A function that can take an array of string and return colored tags
 */
function displayTags($tagList, $hyperlink = true, TagType $tagType = TagType::Games): string
{
    if (count($tagList) <= 0) {
        return "";
    }

    $colors = ["0", "1", "2", "3"]; // There are 4 colors for tags on Sploder

    // Get total number of games for said tag
    require_once('../database/connect.php');
    $db = getDatabase();
    $tagParameters = array_map(function ($tag) {
        return $tag[0];
    }, $tagList);
    $placeholders = implode(',', array_fill(0, count($tagParameters), '?'));
    
    $table = match($tagType) {
        TagType::Graphics => 'graphic_tags',
        TagType::Games => 'game_tags'
    };
    
    $counts = $db->query("SELECT tag, COUNT(g_id) as count
        FROM $table
        WHERE tag IN ($placeholders)
        GROUP BY tag", $tagParameters, PDO::FETCH_KEY_PAIR);

    for ($i = 0; $i < count($tagList); $i++) {
        $tagList[$i][1] = $counts[$tagList[$i][0]] ?? 0;
    }

    $tagString = "";
    if ($hyperlink) {
        [$page, $suffix] = match($tagType) {
            TagType::Graphics => ["graphic-tags.php", "graphic"],
            TagType::Games => ["game-tags.php", "game"]
        };
        
        for ($i = 0; $i < count($tagList); $i++) {
            $tagString .= "<a class=\"tagcolor{$colors[$i % 4]}\" href=\"$page?t={$tagList[$i][0]}\" title=\"{$tagList[$i][0]} - {$tagList[$i][1]} $suffix" . ($tagList[$i][1] == 1 ? "" : "s") . ".\">{$tagList[$i][0]}</a> ";
        }
    } else {
        for ($i = 0; $i < count($tagList); $i++) {
            $tagString .= "<span class=\"tagcolor{$colors[$i % 4]}\">{$tagList[$i][0]}</span> ";
        }
    }
    return $tagString;
}
