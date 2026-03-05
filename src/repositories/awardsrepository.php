<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/iawardsrepository.php");

class AwardsRepository implements IAwardsRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function getAwardCount(string $username): int
    {
        return $this->db->queryFirstColumn("SELECT COUNT(*)
            FROM awards 
            WHERE membername = :membername", 0, [
                ':membername' => $username]);
    }

    public function getAwardsPage(string $username, int $offset, int $limit): array
    {
        return $this->db->query("SELECT *
            FROM awards
            WHERE membername = :membername
            ORDER BY style DESC, material DESC, color DESC, icon DESC
            LIMIT :limit OFFSET :offset", [
                ':membername' => $username,
                ':limit' => $limit,
                ':offset' => $offset*50
            ]);

    }

    public function setAllAwardsAsViewed(string $username): void
    {
        $this->db->execute("UPDATE award_requests
            SET is_viewed = true
            WHERE membername = :membername", [
                ':membername' => $username]);
    }

    public function getAwardRequestCount(string $username, bool $isViewed): int
    {
        return $this->db->queryFirstColumn("SELECT COUNT(*)
            FROM award_requests
            WHERE membername = :membername AND is_viewed = :is_viewed", 0, [
                ':membername' => $username,
                ':is_viewed' => $isViewed
            ]);
    }

}
