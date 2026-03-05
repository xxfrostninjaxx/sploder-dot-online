<?php

require_once(__DIR__ . "/../database/PaginationData.php");
require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/igamerepository.php");

class GameRepository implements IGameRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    function trackView(int $game_id, string $ip_address, int|null $user_id): void
    {
        $query = "
INSERT INTO game_views_anonymous(g_id, ipaddress) values(:g_id, :ipaddress)
on conflict do nothing;";

        $this->db->execute($query, [
            ':g_id' => $game_id,
            ':ipaddress' => $ip_address,
        ]);

        if ($user_id !== null) {
            $query = "
INSERT INTO game_views_members(g_id, userid) values(:g_id, :userid)
on conflict do nothing;";

            $this->db->execute($query, [
                ':g_id' => $game_id,
                ':userid' => $user_id,
            ]);

            $query = "
UPDATE games
  SET views=(select count(*) from game_views_members gvm where gvm.g_id = :g_id)
where g_id = :g_id
";
            $this->db->execute($query, [
                ':g_id' => $game_id,
            ]);
        }
    }

    public function getGameData(int $gameId): GameData
    {
        $gameInfo = $this->db->queryFirst("SELECT author, difficulty FROM games WHERE g_id = :g_id", [
            ':g_id' => $gameId,
        ]);

        $avgScore = $this->db->queryFirstColumn("SELECT AVG(score) as avg FROM votes WHERE g_id = :g_id", 0, [
            ':g_id' => $gameId,
        ]);

        if ($avgScore === null) {
            $avgScore = 0;
        } else {
            $avgScore = round($avgScore);
        }

        return new GameData($gameInfo['author'], round($gameInfo['difficulty']), $avgScore);
    }

    public function getGameTags(int $offset, int $perPage): PaginationData
    {
        return $this->db->queryPaginated(
            "SELECT DISTINCT gt.tag, COUNT(*) AS game_count 
            FROM game_tags gt
            JOIN games g ON gt.g_id = g.g_id
            WHERE g.ispublished = 1 AND g.isprivate = 0
            GROUP BY gt.tag ORDER BY game_count DESC, gt.tag",
            $offset,
            $perPage
        );
    }

    public function getTagsFromGame(int $gameId): array
    {
        return $this->db->query("SELECT tag FROM game_tags WHERE g_id = :g_id", [
            ':g_id' => $gameId,
        ]);
    }

    public function getUserId(int $gameId): string
    {
        return $this->db->queryFirstColumn("SELECT user_Id FROM games WHERE g_id = :g_id", 0, [
            ':g_id' => $gameId,
        ]);
    }

    public function getRandomGames(): array
    {
        $query = "SELECT g_id, title, author, user_id FROM games WHERE ispublished = 1 AND isprivate = 0 ORDER BY RANDOM() LIMIT 6";
        return $this->db->query($query);
    }

    public function getWeirdRandomGames(): array
    {
        $query = "SELECT g_id, title, author, user_id FROM games WHERE ispublished = 1 AND isprivate = 0 ORDER BY RANDOM() LIMIT 22";
        return $this->db->query($query);
    }

    public function getPendingDeletionGames(): array
    {
        return $this->db->query("SELECT
            games.g_id, games.first_published_date, MIN(pending_deletions.timestamp) as deletion_date, g_swf, author, title, userid as user_id, reason, views
            FROM pending_deletions
            JOIN games ON games.g_id = pending_deletions.g_id 
            JOIN members ON games.author = members.username 
            WHERE pending_deletions.timestamp = (SELECT MIN(timestamp) FROM pending_deletions pd WHERE pd.g_id = games.g_id)
            GROUP BY games.g_id, g_swf, author, title, userid, reason, views");
    }

    public function getPublicGamesFromUser(string $userName, int $offset, int $perPage): PaginationData
    {
        $qs = "SELECT g.author, g.title, g.description, g.g_id, g.user_id, g.g_swf, g.first_published_date, g.user_id, g.views, 
            ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes 
            FROM games g 
            LEFT JOIN votes r ON g.g_id = r.g_id 
            WHERE ((g.ispublished = 1 AND g.isprivate = 0) AND :isDeleted = 0)
            AND g.author = :userName
            AND g.isdeleted = :isDeleted
            GROUP BY g.g_id 
            ORDER BY g.first_published_date DESC";

        return $this->db->queryPaginated($qs, $offset, $perPage, [
            ':userName' => $userName,
            ':isDeleted' => 0,
        ]);
    }

    public function getAllGamesFromUser(string $userName, int $offset, int $perPage, bool $isDeleted): PaginationData
    {

        $qs = "SELECT g.author, g.title, g.description, g.g_id, g.user_id, g.g_swf, g.date, g.first_created_date, g.user_id, g.views, g.ispublished,g.isprivate, g.ispublished,
            ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes,
            c.challenge_id as challenge_id
        FROM games g
        LEFT JOIN votes r ON g.g_id = r.g_id
        LEFT JOIN challenges c ON g.g_id = c.g_id
        WHERE g.author = :userName AND g.isDeleted = :isDeleted
        GROUP BY g.g_id, c.challenge_id
        ORDER BY g.date DESC";

        return $this->db->queryPaginated($qs, $offset, $perPage, [
            ':userName' => $userName,
            ':isDeleted' => $isDeleted ? 1 : 0,
        ]);
    }

    public function getGamesFromUserAndGameSearch(string $userName, string $game, int $offset, int $perPage, $isDeleted): PaginationData
    {
        $qs = "
            SELECT * FROM (
                SELECT g.author, g.title, g.description, g.g_id, g.user_id, g.g_swf, g.date, g.first_created_date, g.user_id, g.views, g.ispublished, g.isprivate, g.ispublished,
                    ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes,
                    c.challenge_id as challenge_id,
                    SIMILARITY(g.title, :game) as similarity_score
                FROM games g
                LEFT JOIN votes r ON g.g_id = r.g_id
                LEFT JOIN challenges c ON g.g_id = c.g_id
                WHERE g.author = :userName
                  AND g.isdeleted = :isDeleted
                  AND g.title % :game
                GROUP BY g.g_id, g.title, c.challenge_id
            ) sub
            WHERE similarity_score > 0.3
            ORDER BY similarity_score DESC, date DESC
        ";
        return $this->db->queryPaginated($qs, $offset, $perPage, [
            ':userName' => $userName,
            ':game' => $game,
            ':isDeleted' => $isDeleted,
        ]);
    }

    public function getGamesNewest(int $offset, int $perPage): PaginationData
    {
        return $this->db->queryPaginated("SELECT g.g_id, g.author, g.title, g.description, g.user_id, g.g_swf, g.first_published_date, g.user_id, g.views, g.ispublished,
            ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes 
            FROM games g 
            LEFT JOIN votes r ON g.g_id = r.g_id 
            WHERE g.ispublished = 1
            AND g.isprivate = 0
            and g.isdeleted = 0
            GROUP BY g.g_id 
            ORDER BY g.first_published_date DESC", $offset, $perPage);
    }

    public function getGamesNewestByName(string $game, int $offset, int $perPage): PaginationData
    {
        return $this->db->queryPaginated("
            SELECT * FROM (
                SELECT g.g_id, g.author, g.title, g.description, g.user_id, g.g_swf, g.first_published_date, g.user_id, g.views, g.ispublished,
                    ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes, SIMILARITY(g.title, :game) as similarity_score
                FROM games g
                LEFT JOIN votes r ON g.g_id = r.g_id
                WHERE g.ispublished = 1
                  AND g.isprivate = 0
                  AND g.isdeleted = 0
                  AND g.title % :game
                GROUP BY g.g_id
            ) sub
            WHERE similarity_score > 0.1
            ORDER BY similarity_score DESC
        ", $offset, $perPage, [
            ':game' => $game,
        ]);
    }

    public function getGamesWithTag(string $tag, int $offset, int $perPage): PaginationData
    {
        $qs = "SELECT g.author, g.title, g.description, g.g_id, g.user_id, g.g_swf, g.first_published_date, g.user_id, g.views, g.ispublished,
            ROUND(AVG(r.score), 1) as avg_rating, COUNT(r.score) as total_votes 
            FROM games g 
            JOIN game_tags gt ON g.g_id = gt.g_id 
            LEFT JOIN votes r ON g.g_id = r.g_id 
            WHERE g.ispublished = 1 AND g.isprivate = 0 AND gt.tag = :tag
            GROUP BY g.g_id 
            ORDER BY g.first_published_date DESC";

        return $this->db->queryPaginated($qs, $offset, $perPage, [
            ':tag' => $tag,
        ]);
    }

    public function removeOldPendingDeletionGames(int $daysOld): void
    {
        // Remove pending deletions older than 14 days
        $this->db->execute("DELETE FROM pending_deletions
            WHERE timestamp < NOW() - MAKE_INTERVAL(DAYS => :daysOld)", [
            ':daysOld' => $daysOld
        ]);
    }

    // TODO: move this to the contest repository
    public function getContestWinners(int $pageNumber): array
    {
        if ($pageNumber < 1) {
            return [];
        }
        
        $page = $pageNumber - 1;

        $query = "
        WITH filtered_winners AS (
            SELECT cw.contest_id, cw.g_id
            FROM contest_winner cw
            JOIN games g ON cw.g_id = g.g_id
            WHERE g.isprivate = 0 AND g.ispublished = 1 AND g.isdeleted = 0
        ),
        total AS (
            SELECT COUNT(*) AS cnt FROM filtered_winners
        ),
        numbered AS (
            SELECT
                contest_id,
                g_id,
                ROW_NUMBER() OVER (ORDER BY contest_id ASC) AS rn
            FROM filtered_winners
        )
        SELECT g.g_id, g.title, g.author, g.user_id
        FROM numbered n
        JOIN total t ON true
        JOIN games g ON g.g_id = n.g_id
        WHERE (
            (:page = 0 AND n.rn <= (t.cnt % 6))
            
            OR
            
            (:page > 0 
                AND n.rn > (t.cnt % 6) + (:page - 1) * 6
                AND n.rn <= (t.cnt % 6) + :page * 6)
        )
        ORDER BY n.contest_id DESC;
        ";

        return $this->db->query($query, ['page' => $page]);
    }

        public function getTotalPublishedGameCount(): int
        {
            return $this->db->queryFirstColumn("SELECT COUNT(g_id)
                FROM games
                WHERE ispublished = 1
                AND isprivate = 0", 0);
        }

        public function getTotalDeletedGameCount($userName): int
        {
            return $this->db->queryFirstColumn("SELECT g_id
                FROM games
                WHERE author=:user
                AND isdeleted=1", 0, [
                    ':user' => $userName
                ]);
        }

    public function getTotalMetricsForUser(string $userName): GameMetricsForUser
    {
        $metrics = $this->db->queryFirst("SELECT COALESCE(count(g_id), 0) as totalGames, COALESCE(sum(views), 0) as totalViews
            FROM games
            WHERE author=:user
            AND isdeleted=0", [
            ':user' => $userName,
        ], PDO::FETCH_NUM);
        return new GameMetricsForUser((int)$metrics[1], (int)$metrics[0]);
    }

    public function verifyOwnership(int $gameId, string $userName): bool
    {
        $query = "SELECT author FROM games WHERE g_id = :g_id";
        $result = $this->db->queryFirstColumn($query, 0, [
            ':g_id' => $gameId,
        ]);
        return $result === $userName;
    }

    public function allowComment(int $gameId): bool
    {
        $query = "SELECT comments FROM games WHERE g_id = :g_id";
        $result = $this->db->queryFirstColumn($query, 0, [
            ':g_id' => $gameId,
        ]);
        return $result === 1;
    }
    
    public function getGameBasicInfo(int $gameId): array
    {
        $result = $this->db->queryFirst("SELECT title, author, g_swf FROM games WHERE g_id = :g_id", [
            ':g_id' => $gameId,
        ]);
        
        return [
            'title' => $result['title'],
            'author' => $result['author'],
            'g_swf' => (int)$result['g_swf']
        ];
    }

    public function publishGame(int $id, bool $private, string $comments): void {
        $this->db->execute("UPDATE games
        SET ispublished = :ispublished, 
            isprivate = :isprivate, 
            comments = :comments, 
            first_published_date = CASE 
                WHEN first_published_date = '1970-01-01 00:00:00' AND :isprivate = 0 THEN :current_date 
                ELSE first_published_date 
            END,
            last_published_date = :current_date,
            date = :current_date
        WHERE g_id = :id", [
        ':ispublished' => 1,
        ':isprivate' => $private,
        ':comments' => $comments,
        ':current_date' => date("Y-m-d H:i:s"),
        ':id' => $id
    ]);
    }

    public function setFeaturedStatus(int $id, bool $feature, int $editorUserId): void {
        if ($feature) {
            $this->db->execute("INSERT INTO featured_games (g_id, feature_date, editor_userid) VALUES (:g_id, :feature_date, :editor_userid) ON CONFLICT (g_id) DO NOTHING", [
                ':g_id' => $id,
                ':feature_date' => date("Y-m-d H:i:s"),
                ':editor_userid' => $editorUserId
            ]);
        } else {
            $this->db->execute("DELETE FROM featured_games WHERE g_id = :g_id", [
                ':g_id' => $id
            ]);
        }
    }

    public function getFeaturedStatus(int $id): bool {
        $result = $this->db->queryFirstColumn("SELECT COUNT(*) FROM featured_games WHERE g_id = :g_id", 0, [
            ':g_id' => $id
        ]);
        return $result > 0;
    }

    public function getFeaturedGames(int $offset, int $perPage): PaginationData {
        if ($offset === 0) {
            // First page: get 2 contest winners to display
            $contestGamesQuery = "
                SELECT g.g_id, g.title, g.author, g.g_swf, g.first_published_date, g.views, g.user_id, NULL AS feature_date,
                    ROUND(AVG(v.score), 1) as avg_rating, COUNT(v.score) as total_votes,
                    TRUE AS contest_game
                FROM contest_winner cw
                JOIN games g ON cw.g_id = g.g_id
                LEFT JOIN votes v ON g.g_id = v.g_id
                WHERE g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0
                GROUP BY g.g_id, g.title, g.author, g.g_swf, g.first_published_date, g.views, g.user_id, cw.contest_id
                ORDER BY cw.contest_id DESC, RANDOM()
                LIMIT 2
            ";

            $contestGames = $this->db->query($contestGamesQuery);
            
            $displayedContestGameIds = array_map(fn($game) => $game['g_id'], $contestGames);

            $exclusionClause = '';
            $params = [];
            if (!empty($displayedContestGameIds)) {
                $placeholders = [];
                foreach ($displayedContestGameIds as $index => $gId) {
                    $paramName = ':excluded_g_id_' . $index;
                    $placeholders[] = $paramName;
                    $params[$paramName] = $gId;
                }
                $exclusionClause = ' AND games.g_id NOT IN (' . implode(', ', $placeholders) . ')';
            }

            $query = "
                SELECT games.g_id, games.title, games.author, games.g_swf, games.first_published_date, games.views, games.user_id, featured_games.feature_date,
                    ROUND(AVG(v.score), 1) as avg_rating, COUNT(v.score) as total_votes,
                    FALSE AS contest_game
                FROM featured_games
                JOIN games ON featured_games.g_id = games.g_id
                LEFT JOIN votes v ON games.g_id = v.g_id
                WHERE games.ispublished = 1 AND games.isprivate = 0 AND games.isdeleted = 0
                " . $exclusionClause . "
                GROUP BY games.g_id, games.title, games.author, games.g_swf, games.first_published_date, games.views, games.user_id, featured_games.feature_date
                ORDER BY featured_games.feature_date DESC
                LIMIT " . $perPage;

            $normalGames = $this->db->query($query, $params);

            $totalQuery = "
                SELECT COUNT(DISTINCT games.g_id)
                FROM featured_games
                JOIN games ON featured_games.g_id = games.g_id
                WHERE games.ispublished = 1 AND games.isprivate = 0 AND games.isdeleted = 0
                " . $exclusionClause;

            $totalNormalGames = $this->db->queryFirstColumn($totalQuery, 0, $params);

            // Combine results
            $allGames = array_merge($contestGames, $normalGames);

            return new PaginationData($allGames, $totalNormalGames, ceil($totalNormalGames / $perPage));
        } else {
            // For pages after the first, show only featured games (no contest exclusion)
            $query = "
                SELECT games.g_id, games.title, games.author, games.g_swf, games.first_published_date, games.views, games.user_id, featured_games.feature_date,
                    ROUND(AVG(v.score), 1) as avg_rating, COUNT(v.score) as total_votes,
                    FALSE AS contest_game
                FROM featured_games
                JOIN games ON featured_games.g_id = games.g_id
                LEFT JOIN votes v ON games.g_id = v.g_id
                WHERE games.ispublished = 1 AND games.isprivate = 0 AND games.isdeleted = 0
                GROUP BY games.g_id, games.title, games.author, games.g_swf, games.first_published_date, games.views, games.user_id, featured_games.feature_date
                ORDER BY featured_games.feature_date DESC
            ";
            
            return $this->db->queryPaginated($query, $offset, $perPage, []);
        }
    }

    public function getReviewData(int $userId, int $gameId): array {
        $query = "SELECT r.title, r.review, r.ispublished, r.review_date, r.review_id, r. userid, m.username 
          FROM reviews r
          JOIN members m ON r.userid = m.userid
          WHERE r.userid = :userid AND r.g_id = :g_id";
        $result =  $this->db->queryFirst($query, [
            ':userid' => $userId,
            ':g_id' => $gameId
        ]);
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    public function saveReview(int $userId, int $gameId, string $title, string $review, bool $isPublished): void {
        $this->db->execute("INSERT INTO reviews (userid, g_id, title, review, ispublished, review_date) 
        VALUES (:userid, :g_id, :title, :review, :ispublished, :review_date)
        ON CONFLICT (userid, g_id) DO UPDATE 
        SET title = :title, review = :review, ispublished = :ispublished, review_date = :review_date", [
            ':userid' => $userId,
            ':g_id' => $gameId,
            ':title' => $title,
            ':review' => $review,
            ':ispublished' => $isPublished,
            ':review_date' => date("Y-m-d H:i:s")
        ]);
    }

    public function getPublicReviews(int $offset, int $perPage): PaginationData {
        $query = "SELECT r.userid, r.g_id, r.title,
        LEFT(r.review, 316) || CASE WHEN LENGTH(r.review) > 316 THEN ' ...' ELSE '' END AS review,
        r.review_date, m.username as author, g.user_id as game_author_id, g.title as game_title, g.g_swf
        FROM reviews r
        JOIN members m ON r.userid = m.userid
        JOIN games g ON r.g_id = g.g_id
        WHERE r.ispublished = true AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0
        ORDER BY r.review_date DESC";

        return $this->db->queryPaginated($query, $offset, $perPage);
    }

    public function getReviewsForGame(int $gameId): array {
        $query = "SELECT DISTINCT r.userid, m.username, r.title 
                  FROM reviews r 
                  JOIN members m ON r.userid = m.userid 
                  WHERE r.g_id = :g_id AND r.ispublished = true";
        $result = $this->db->query($query, [
            ':g_id' => $gameId
        ]);
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    public function getReviewsByUsername(string $username): array {
        $query = "SELECT r.userid, r.g_id, r.title, g.author, g.user_id as game_author_id, g.title as game_title 
                  FROM reviews r 
                  JOIN members m ON r.userid = m.userid 
                  JOIN games g ON r.g_id = g.g_id
                  WHERE m.username = :username AND r.ispublished = true AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0
                  ORDER BY r.review_date DESC";
        $result = $this->db->query($query, [
            ':username' => $username
        ]);
        if (empty($result)) {
            return [];
        }
        return $result;
    }

    public function getAllReviewsByUsername(string $username, int $offset, int $perPage): PaginationData {
        $query = "SELECT r.userid, r.g_id, r.title, r.ispublished,
        LEFT(r.review, 316) || CASE WHEN LENGTH(r.review) > 316 THEN ' ...' ELSE '' END AS review,
        r.review_date, m.username as author, g.user_id as game_author_id, g.title as game_title, g.g_swf
        FROM reviews r
        JOIN members m ON r.userid = m.userid
        JOIN games g ON r.g_id = g.g_id
        WHERE m.username = :username
        ORDER BY r.review_date DESC";

        return $this->db->queryPaginated($query, $offset, $perPage, [
            ':username' => $username
        ]);
    }

    public function deleteReview($userId, $gameId): void {
        $this->db->execute("DELETE FROM reviews WHERE userid = :userid AND g_id = :g_id", [
            ':userid' => $userId,
            ':g_id' => $gameId
        ]);
    }

    public function hasUserReviewedGame(int $userId, int $gameId): bool {
        $result = $this->db->queryFirstColumn("SELECT COUNT(*) FROM reviews WHERE userid = :userid AND g_id = :g_id", 0, [
            ':userid' => $userId,
            ':g_id' => $gameId
        ]);
        return $result > 0;
    }

    public function getGameSFromReviewId(int $reviewId): string {
        $query = "SELECT g.g_id, m.userid AS game_author_id, r.userid AS review_author_id
        FROM reviews r
        JOIN games g ON r.g_id = g.g_id
        JOIN members m ON g.author = m.username
        WHERE r.review_id = :review_id";

        $result = $this->db->queryFirst($query, [
            ':review_id' => $reviewId
        ]);
        if ($result === null) {
            return '0_0';
        }

        return $result[1] . '_' . $result[0] . '_' . $result[2];
    }
}