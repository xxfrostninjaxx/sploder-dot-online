<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/ichallengesrepository.php");

class ChallengesRepository implements IChallengesRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function addChallenge(int $gameId, bool $mode, int $challenge, int $prize, int $winners)
    {
        $query = "INSERT INTO challenges (g_id, mode, challenge, prize, winners, verified, insert_date) VALUES (:g_id, :mode, :challenge, :prize, :winners, :verified, NOW())";
        $this->db->execute($query, [
            ':g_id' => $gameId,
            ':mode' => $mode,
            ':challenge' => $challenge,
            ':prize' => $prize,
            ':winners' => $winners,
            ':verified' => false,
        ]);
    }

    public function getChallengeInfo(int $gameId): array|false
    {
        $query = "SELECT mode,prize,challenge FROM challenges WHERE g_id = :id";
        return $this->db->queryFirst($query, [':id' => $gameId]);
    }

    public function verifyIfSIsCorrect(int $gameId, int $userId): bool
    {
        $query = "SELECT user_id FROM games WHERE g_id = :g_id";
        $result = $this->db->queryFirst($query, [':g_id' => $gameId]);
        return $result['user_id'] === $userId;
    }

    public function getChallengeId(int $gameId): int|null
    {
        $query = "SELECT challenge_id FROM challenges WHERE g_id = :g_id";
        $result = $this->db->queryFirst($query, [':g_id' => $gameId])['challenge_id'];
        // If $result is not an integer, return null
        return is_int($result) ? $result : null;
    }

    public function verifyChallengeId(int $gameId, int $challengeId, int $sessionChallengeId): bool
    {
        $query = "SELECT c.challenge_id
          FROM challenges c
          LEFT JOIN challenge_winners w ON w.g_id = c.g_id
          WHERE c.g_id = :g_id
            AND c.insert_date > NOW() - INTERVAL '15 days'
          GROUP BY c.challenge_id, c.winners
          HAVING COUNT(w.winner_id) < c.winners";
        $result = $this->db->queryFirst($query, [':g_id' => $gameId]);
        return ($result['challenge_id'] === $challengeId) && ($result['challenge_id'] === $sessionChallengeId);
    }

    public function unverifyChallenge(int $gameId): void
    {
        // Unverify the challenge, if it exists in the first place
        $query = "UPDATE challenges SET verified = false WHERE g_id = :g_id";
        $this->db->execute($query, [':g_id' => $gameId]);
    }

    public function getAllChallenges(int $offset, int $perPage): array
    {

        $query = "SELECT c.challenge_id, c.g_id, c.mode, c.challenge, c.prize, c.winners, c.verified, 
                 c.insert_date + INTERVAL '15 days' AS expires_at, 
                 g.user_id, g.title, g.author,
                 COUNT(w.winner_id) AS total_winners
          FROM challenges c
          JOIN games g ON c.g_id = g.g_id
          LEFT JOIN challenge_winners w ON w.g_id = c.g_id
          WHERE c.insert_date > NOW() - INTERVAL '15 days'
            AND g.isprivate = 0 AND g.ispublished = 1 AND g.isdeleted = 0
          GROUP BY c.challenge_id, c.g_id, c.mode, c.challenge, c.prize, c.winners, c.verified, c.insert_date, g.user_id, g.title, g.author
          HAVING COUNT(w.winner_id) < c.winners
          ORDER BY c.verified DESC OFFSET :offset LIMIT :perPage";
        return $this->db->query($query, [
            ':offset' => $offset*$perPage,
            ':perPage' => $perPage,
        ]);
    }

    public function checkIfChallengeCreatorIsOwner(int $challengeId, int $userId): bool
    {
        $query = "SELECT g.user_id FROM challenges c JOIN games g ON c.g_id = g.g_id WHERE c.challenge_id = :challenge_id";
        $result = $this->db->queryFirst($query, [':challenge_id' => $challengeId]);
        return $result['user_id'] === $userId;
    }

    public function verifyChallenge(int $challengeId): bool
    {
        $query = "UPDATE challenges SET verified = true WHERE challenge_id = :challenge_id";
        $this->db->execute($query, [':challenge_id' => $challengeId]);
        return true;
    }

    public function addChallengeWinner(int $challengeId, int $userId): bool
    {
        $query = "INSERT INTO challenge_winners (g_id, user_id) VALUES (:g_id, :user_id)";
        $this->db->execute($query, [
            ':g_id' => $challengeId,
            ':user_id' => $userId,
        ]);
        return true;
    }

    public function hasWonChallenge(int $g_id, int $userId): bool
    {
        $query = "SELECT COUNT(*) FROM challenge_winners WHERE g_id = :g_id AND user_id = :user_id";
        $result = $this->db->queryFirst($query, [
            ':g_id' => $g_id,
            ':user_id' => $userId,
        ]);
        return $result['count'] > 0;
    }

    public function getTotalChallengeCount(): int
    {
        $query = "SELECT COUNT(*) AS count FROM (
            SELECT c.challenge_id
            FROM challenges c
            JOIN games g ON c.g_id = g.g_id
            LEFT JOIN challenge_winners w ON w.g_id = c.g_id
            WHERE c.insert_date > NOW() - INTERVAL '15 days'
            AND g.isprivate = 0 AND g.ispublished = 1 AND g.isdeleted = 0
            GROUP BY c.challenge_id, c.g_id, c.mode, c.challenge, c.prize, c.winners, c.verified, c.insert_date, g.user_id, g.title, g.author
            HAVING COUNT(w.winner_id) < c.winners
        ) sub";
        return $this->db->queryFirst($query)['count'];
    }
}