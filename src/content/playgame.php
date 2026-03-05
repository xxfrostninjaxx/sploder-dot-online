<?php

interface ICreatorType
{
    function id(): int;
    function name(): string;
    function url(): string;
    function swf_version(): string;
}

enum CreatorType: int implements ICreatorType
{
    case UNDEFINED = 0;
    case SHOOTER = 1;
    case PLATFORMER = 2;
    case ALGORITHM = 3;
    case PHYSICS = 5;
    case ARCADE = 7;

    public function id(): int
    {
        return $this->value;
    }

    public function name(): string
    {
        return match ($this) {
            CreatorType::SHOOTER => "classic",
            CreatorType::PLATFORMER => "platformer",
            CreatorType::ALGORITHM => "3d adventure",
            CreatorType::PHYSICS => "physics",
            CreatorType::ARCADE => "arcade",
            CreatorType::UNDEFINED => throw new Exception("Undefined CreatorType used", 1),
            _ => throw new Exception("Unknown CreatorType " . $this, 1),
        };
    }

    public function url(): string
    {
        return match ($this) {
            CreatorType::SHOOTER => "shooter",
            CreatorType::PLATFORMER => "plat",
            CreatorType::ALGORITHM => "algo",
            CreatorType::PHYSICS => "ppg",
            CreatorType::ARCADE => "arcade",
            CreatorType::UNDEFINED => throw new Exception("Undefined CreatorType used", 1),
            _ => throw new Exception("Unknown CreatorType " . $this, 1),
        };
    }

    public function swf_version(): string
    {
        return match ($this) {
            CreatorType::PLATFORMER => "20",
            CreatorType::SHOOTER => "13",
            CreatorType::ALGORITHM => "idk",
            CreatorType::PHYSICS => "28",
            CreatorType::ARCADE => "idk",
            CreatorType::UNDEFINED => throw new Exception("Undefined CreatorType used", 1),
            _ => throw new Exception("Unknown CreatorType " . $this, 1),
        };
    }
}

function to_creator_type($g_swf): ICreatorType
{
    return CreatorType::from($g_swf);
}


function get_game_info($game_id)
{
    require_once('../database/connect.php');
    $db = getDatabase();
    $qs = "SELECT * FROM games WHERE g_id=:game_id";
    $game = $db->queryFirst($qs, [':game_id' => $game_id]);
    if (!isset($game['title'])) {
        die("Invalid game ID");
    }
    return $game;
}