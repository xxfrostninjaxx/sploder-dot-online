<?php

require_once(__DIR__ . "/idatabase.php");

interface IDatabaseManager
{
    public function getPostgresDatabase(): IDatabase;
    public function getOriginalMembersDatabase(): IDatabase;
}
