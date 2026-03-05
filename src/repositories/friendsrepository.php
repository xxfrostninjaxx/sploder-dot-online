<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/ifriendsrepository.php");

class FriendsRepository implements IFriendsRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function getFriendRequestCount(int $userId, bool $isViewed): int
    {
        $isViewedStr = $isViewed ? 'true' : 'false'; // Convert boolean to string
        $newFriends = $this->db->queryFirstColumn("SELECT count(*) FROM friend_requests WHERE receiver_id=:user AND is_viewed=:is_viewed", 0, [
            ':user' => $userId,
            ':is_viewed' => $isViewedStr
        ]);
        return $newFriends;
    }

    public function setAllFriendsAsViewed(int $userId): void
    {
        $this->db->execute("UPDATE friend_requests SET is_viewed=true WHERE receiver_id=:receiver_id", [
            ':receiver_id' => $userId
        ]);
    }

    public function alreadyFriends(string $sender, string $receiver): bool
    {
        $query = "SELECT id FROM friends WHERE user1 = :sender AND user2 = :receiver";
        $result = $this->db->query($query, [
            ':sender' => $sender,
            ':receiver' => $receiver
        ]);
        
        return !empty($result);
    }

    public function getTotalFriends(string $username): int
    {
        $query = "SELECT COUNT(*) FROM friends WHERE user1 = :username";
        $result = $this->db->queryFirstColumn($query, 0, [':username' => $username]);
        
        return (int)$result;
    }

    public function getBestedFriends(string $username, int $limit = 30): array
    {
        return $this->db->query("SELECT user1, user2
            FROM friends
            WHERE (bested = true)
            AND (user1 = :sender_id)
            ORDER BY id DESC
            LIMIT :limit", [
                ':sender_id' => $username,
                ':limit' => $limit
            ]);
    }

    public function getAcceptedFriends(string $username, int $limit): array
    {
        return $this->db->query("SELECT user1, user2
            FROM friends
            WHERE (bested = false)
            AND (user1 = :sender_id)
            ORDER BY id DESC 
            LIMIT :limit", [
                ':sender_id' => $username,
                ':limit' => $limit
            ]);
    }

    public function search(string $username, string $search, int $page, int $perPage): PaginationData
    {
        $hasSearch = !empty($search);
        $query = "WITH friends_with_sim AS (
            SELECT
                user2 AS user1, id,
                bested,
                CASE WHEN :hasSearch = 1 THEN similarity(:search, user2) ELSE 1.0 END AS sim
            FROM
                friends
            WHERE
                user1 = :username
        )
        SELECT
            user1,
            bested
        FROM
            friends_with_sim
        WHERE
            sim > 0.3
        ORDER BY
            bested DESC,
            sim DESC,
            id DESC";
        return $this->db->queryPaginated($query, $page, $perPage, [
            ':username' => $username,
            ':search' => $search,
            ':hasSearch' => $hasSearch
        ]);
    }
}