<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/iuserrepository.php");

class UserRepository implements IUserRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function search(string $userName, int $limit = 180)
    {
        $qs = "
SELECT
    m.username AS user1,
    SIMILARITY(m.username, :u) AS sim,
    LEAST(250, FLOOR(
        (SELECT COUNT(*) FROM votes v JOIN games g ON v.g_id = g.g_id WHERE g.author = m.username)/25.0
        + (SELECT COUNT(DISTINCT f.user2) FROM friends f WHERE f.user1 = m.username)/10.0
        + (SELECT COUNT(DISTINCT g.g_id) FROM games g WHERE g.author = m.username)/10.0
        + (SELECT COALESCE(SUM(g2.views),0) FROM games g2 WHERE g2.author = m.username)/1000.0
    ) + 1) AS level
FROM members m
WHERE SIMILARITY(m.username, :u) > 0.3
ORDER BY sim DESC
LIMIT :limit";
        return $this->db->query($qs, [
            ':u' => trim($userName ?? ''),
            ':limit' => $limit,
        ]);
    }

    public function getTopMembers()
    {
        $qs = "
        WITH latest_view AS (
    SELECT MAX(create_date) AS max_time
    FROM (
        SELECT MAX(create_date) AS create_date FROM public.game_views_members
        UNION ALL
        SELECT MAX(create_date) FROM public.game_views_anonymous
    ) AS combined_views
)
SELECT 
    g.author, 
    COUNT(gv.g_id) + COUNT(ga.g_id) AS total_views, 
    COALESCE(
        MAX(gv.create_date), 
        MAX(ga.create_date), 
        (SELECT max_time FROM latest_view)
    ) AS last_view_time
FROM public.games g
LEFT JOIN public.game_views_members gv 
    ON g.g_id = gv.g_id 
    AND gv.create_date >= (SELECT max_time FROM latest_view) - INTERVAL '24 hours'
LEFT JOIN public.game_views_anonymous ga 
    ON g.g_id = ga.g_id 
    AND ga.create_date >= (SELECT max_time FROM latest_view) - INTERVAL '24 hours'
GROUP BY g.author
HAVING COUNT(gv.g_id) + COUNT(ga.g_id) > 0
ORDER BY total_views DESC
LIMIT 90;
";
        return $this->db->query($qs);
    }

    private function getLevel(int $rating, int $friends, int $games, int $views)
    {
        $level = min(250, floor($rating/25 + $friends/10 + $games/10 + $views/1000)+1);
        return $level;
    }

    public function getMembers(int $offset)
    {
    $offset = $offset * 100;
    $qs = "
        SELECT
            m.username,
            m.joindate,
            COUNT(DISTINCT f.user2) AS friend_count,
            COUNT(DISTINCT g.g_id) FILTER (WHERE g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0) AS game_count,
            (SELECT COALESCE(SUM(g.views), 0) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0) AS total_views,
            (SELECT COUNT(1) FROM votes WHERE g_id IN (SELECT g_id FROM games WHERE author = m.username AND ispublished = 1 AND isprivate = 0 AND isdeleted = 0)) AS total_ratings_received
        FROM
            members m
        LEFT JOIN
            friends f ON m.username = f.user1
        LEFT JOIN
            games g ON m.username = g.author
        GROUP BY
            m.username, m.joindate
        ORDER BY
            m.joindate
        LIMIT 100 OFFSET :offset
    ";
        $result = $this->db->query($qs, [':offset' => $offset]);
        foreach ($result as &$row) {
            $row['level'] = $this->getLevel(
                $row['total_ratings_received'],
                $row['friend_count'],
                $row['game_count'],
                $row['total_views']
            );
        }   
        return $result;
    }

    public function getTotalNumberOfMembers(): int
    {
        $qs = "SELECT COUNT(*) FROM members";
        return $this->db->query($qs)[0]['count'];
    }

    public function getOnlineMembers(): array
    {
        $last = time() - 120; # 120 seconds
        $qs = "
            SELECT
            m.username,
            m.lastlogin,
            m.lastpagechange,
            m.status,
            (SELECT COUNT(1) FROM votes WHERE g_id IN (SELECT g_id FROM games WHERE author = m.username AND ispublished = 1 AND isprivate = 0 AND isdeleted = 0)) AS total_ratings_received,
            (SELECT COUNT(DISTINCT f.user2) FROM friends f WHERE f.user1 = m.username) AS friend_count,
            (SELECT COUNT(DISTINCT g.g_id) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0) AS game_count,
            (SELECT COALESCE(SUM(g.views), 0) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0) AS total_views,
            LEAST(250, FLOOR(
                (SELECT COUNT(1) FROM votes WHERE g_id IN (SELECT g_id FROM games WHERE author = m.username AND ispublished = 1 AND isprivate = 0 AND isdeleted = 0))/25.0
                + (SELECT COUNT(DISTINCT f.user2) FROM friends f WHERE f.user1 = m.username)/10.0
                + (SELECT COUNT(DISTINCT g.g_id) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0)/10.0
                + (SELECT COALESCE(SUM(g.views), 0) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0)/1000.0
            ) + 1) AS level
        FROM
            members m
        WHERE
            m.lastlogin > :last
        ORDER BY level DESC
        LIMIT 15";
        return $this->db->query($qs, [':last' => $last]);
    }

    public function getLevelByUserId(int $userId)
    {
        $qs = "
            SELECT 
                (SELECT COUNT(1) FROM votes WHERE g_id IN (SELECT g_id FROM games WHERE author = m.username AND ispublished = 1 AND isprivate = 0 AND isdeleted = 0)) AS total_ratings_received,
                (SELECT COUNT(DISTINCT f.user2) FROM friends f WHERE f.user1 = m.username) AS friend_count,
                (SELECT COUNT(DISTINCT g.g_id) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0) AS game_count,
                (SELECT COALESCE(SUM(g.views), 0) FROM games g WHERE g.author = m.username AND g.ispublished = 1 AND g.isprivate = 0 AND g.isdeleted = 0) AS total_views
            FROM 
                members m
            WHERE 
                m.userid = :id
            LIMIT 1
        ";

        $result = $this->db->queryFirst($qs, [':id' => $userId]) ?? null;

        if ($result) {
            return $this->getLevel(
                $result['total_ratings_received'],
                $result['friend_count'],
                $result['game_count'],
                $result['total_views']
            );
        }

        return null; // Return null if no user found
    }

    public function saveEvent(string $s, string $e, string $g)
    {
        $filePath = __DIR__ . '/../cache/events.xml';
        $lockFilePath = __DIR__ . '/../cache/events.lock';

        $lockFile = fopen($lockFilePath, 'w');
        if (flock($lockFile, LOCK_EX)) {
            try {
                $eventsXml = '<root>';
                if (file_exists($filePath) && filesize($filePath) > 0) {
                    $rawData = file_get_contents($filePath);
                    if (trim($rawData) !== '') {
                        $eventsXml .= $rawData;
                    }
                }
                $eventsXml .= '</root>';

                // Load XML with a temporary root
                $events = simplexml_load_string($eventsXml);

                // Add new event
                $event = $events->addChild('evt');
                $event->addAttribute('u', $_SESSION['username']);
                $event->addAttribute('e', $e);
                $event->addAttribute('g', $g);
                $event->addAttribute('s', $s);
                $event->addAttribute('d', round(microtime(true) * 1000));

                // Keep only the last 16 entries
                $children = $events->children();
                while (count($children) > 16) {
                    unset($children[0]);
                }

                // Remove the root and save back in original format
                $newXml = '';
                foreach ($events->evt as $evt) {
                    $newXml .= $evt->asXML() . "\n";
                }

                file_put_contents($filePath, $newXml, LOCK_EX);
            } catch (Exception $ex) {
                error_log('Error saving event: ' . $ex->getMessage());
            } finally {
                flock($lockFile, LOCK_UN);
                fclose($lockFile);
            }
        }
    }

    public function getUserIdFromUsername(string $username): int
    {
        $qs = "SELECT userid FROM members WHERE username = :username";
        $result = $this->db->queryFirst($qs, [':username' => $username]);
        return $result ? (int)$result['userid'] : -1; // Return -1 if not found
    }

    public function getUserStats(string $username): array|null
    {
        $query = "
WITH games_authored_stats AS (
    SELECT
        author,
        COUNT(g_id) AS total_games,
        COALESCE(AVG(difficulty), 5) AS avg_difficulty,
        COALESCE(SUM(views), 0) AS total_views
    FROM games
    WHERE
        author = :username AND
        ispublished = 1 AND
        isprivate = 0 AND
        isdeleted = 0
    GROUP BY author
),
friends_stats AS (
    SELECT
        user1,
        COUNT(DISTINCT user2) AS total_friends
    FROM friends
    WHERE user1 = :username
    GROUP BY user1
),
votes_stats AS (
    SELECT
        g.author,
        COUNT(v.score) AS total_votes,
        COALESCE(AVG(v.score), 3) AS avg_score
    FROM votes v
    JOIN games g ON v.g_id = g.g_id
    WHERE
        g.author = :username AND
        g.ispublished = 1 AND
        g.isprivate = 0 AND
        g.isdeleted = 0
    GROUP BY g.author
),
playtime_stats AS (
    SELECT
        g.author,
        COALESCE(AVG(l.gtm), 0) AS avg_playtime
    FROM leaderboard l
    JOIN games g ON l.pubkey = g.g_id
    WHERE
        g.author = :username AND
        g.ispublished = 1 AND
        g.isprivate = 0 AND
        g.isdeleted = 0
    GROUP BY g.author
),
votes_cast_stats AS (
    SELECT
        username,
        COUNT(score) AS votes_cast_by_user
    FROM votes
    WHERE username = :username
    GROUP BY username
)
SELECT
    :username as username,
    COALESCE(gas.total_games, 0) AS total_games,
    COALESCE(gas.avg_difficulty, 5) AS avg_difficulty,
    COALESCE(fs.total_friends, 0) AS total_friends,
    COALESCE(vs.total_votes, 0) AS total_votes,
    COALESCE(ps.avg_playtime, 0) AS avg_playtime,
    COALESCE(vs.avg_score, 3) AS avg_score,
    -- Perform the level calculation using the pre-calculated values.
    COALESCE(
        floor(
            (
                (COALESCE(vcs.votes_cast_by_user, 0) / 25.0) +
                (COALESCE(fs.total_friends, 0) / 10.0) +
                (COALESCE(gas.total_games, 0) / 10.0) +
                (COALESCE(gas.total_views, 0) / 1000.0)
            ) + 1
        ),
        1
    ) AS calculated_level,
    LEAST(250, COALESCE(
        floor(
            (
                (COALESCE(vcs.votes_cast_by_user, 0) / 25.0) +
                (COALESCE(fs.total_friends, 0) / 10.0) +
                (COALESCE(gas.total_games, 0) / 10.0) +
                (COALESCE(gas.total_views, 0) / 1000.0)
            ) + 1
        ),
        1
    )) AS level
FROM (SELECT :username) as dummy_user
LEFT JOIN games_authored_stats gas ON gas.author = :username
LEFT JOIN friends_stats fs ON fs.user1 = :username
LEFT JOIN votes_stats vs ON vs.author = :username
LEFT JOIN playtime_stats ps ON ps.author = :username
LEFT JOIN votes_cast_stats vcs ON vcs.username = :username;
";

        $result = $this->db->queryFirst($query, [':username' => $username]);
        
        if (!$result) {
            // Return default values if no data found
            return [
                'avg_difficulty' => 50,
                'avg_score' => 50,
                'awesomeness' => 50
            ];
        }

        // Calculate stats and return as array
        $stats = $result;

        // Calculate games score
        $calcGames = 2 * (min(10, $result['total_games']));
        $calcFriends = 2 * (min(5, $result['total_friends']));
        
        // Adjust games and friends calculations
        if ($calcFriends * 2 >= $calcGames) {
            $newCalcFriends = $calcGames;
        } else {
            $newCalcFriends = $calcFriends;
        }
        
        if ($calcGames >= 1) {
            $newCalcGames = $calcFriends;
        } else {
            $newCalcGames = $calcGames;
        }

        // Calculate remaining components
        $calcPlays = floor(0.5 * (min(10, $result['total_votes'] - 0.2)));
        $calcTime = floor(2 * (min(10, $result['avg_playtime'])));
        
        // Convert average score (1-5) to feedback score (0-100) then to final feedback component
        $feedback = floor(min(100/8, (($result['avg_score'] - 1) * 25)/8));
        
        // Calculate level component
        $level = floor(min(250, $result['level']/20));
        
        $compulsory = 24;

        // Calculate awesomeness score
        $awesomeness = min(100, floor(
            $newCalcGames +
            $newCalcFriends +
            $calcPlays +
            $calcTime +
            $compulsory +
            $feedback +
            $level
        ));

        // Return only the required stats
        if ($result['avg_difficulty'] > 1) {
            $avg_difficulty = (int)round(((min(10, $result['avg_difficulty'] + 0.5)) - 1) * (100 / 9));
        } else {
            $avg_difficulty = 0;
        }
        return [
            'avg_difficulty' => $avg_difficulty,
            'avg_score' => (int)round(($result['avg_score'] - 1) * 25),  // Convert to 0-100 scale
            'awesomeness' => $awesomeness
        ];
    }

    public function isIsolated(string $username): bool
    {
        $query = "
            SELECT isolate FROM members WHERE username = :username
        ";
        $result = $this->db->queryFirst($query, [':username' => $username]);
        return $result ? (bool)$result['isolate'] : false; // Return false if not found
    }

    public function setIsolation(string $username, bool $isolate): void
    {
        $query = "
            WITH 
            update_isolation AS (
                UPDATE members 
                SET isolate = :isolate 
                WHERE username = :username
            ),
            delete_friend_requests AS (
                DELETE FROM friend_requests 
                WHERE (:isolate = true) AND (sender_username = :username OR receiver_username = :username)
            ),
            delete_friends AS (
                DELETE FROM friends 
                WHERE (:isolate = true) AND (user1 = :username OR user2 = :username)
            )
            SELECT 1
        ";
    
        $this->db->execute($query, [
            ':isolate' => $isolate,
            ':username' => $username
        ]);

    }
    public function addBoostPoints(int $userId, int $points): void
    {
        $query = "UPDATE members SET boostpoints = boostpoints + :points WHERE userid = :userid";
        $this->db->execute($query, [
            ':points' => $points,
            ':userid' => $userId,
        ]);
    }

    public function removeBoostPoints(int $userId, int $points): void
    {
        $query = "UPDATE members SET boostpoints = boostpoints - :points WHERE userid = :userid";
        $this->db->execute($query, [
            ':points' => $points,
            ':userid' => $userId,
        ]);
    }

    public function getBoostPoints(int $userId): int
    {
        $query = "SELECT boostpoints FROM members WHERE userid = :userid";
        $result = $this->db->queryFirst($query, [':userid' => $userId]);
        return $result ? (int)$result['boostpoints'] : 0;
    }

    public function getUserInfo(string $username): array|false
    {
        $query = "SELECT * FROM user_info WHERE username = :username";
        $result = $this->db->queryFirst($query, [':username' => $username]);
        return $result;
    }

    public function getUserPerms(string $username): string
    {
        $query = "SELECT perms FROM members WHERE username = :username";
        $result = $this->db->queryFirst($query, [':username' => $username]);
        return $result ? (string)$result['perms'] : '';
    }

    public function setUserPerms(string $username, string $perms): bool
    {
        if ($perms === '') {
            // If perms is empty, set to NULL in the database
            $perms = null;
        }
        $query = "UPDATE members SET perms = :perms WHERE username = :username";
        $result = $this->db->execute($query, [
            ':perms' => $perms,
            ':username' => $username
        ]);
        return (bool)$result;
    }

    public function getBanInfo(string $username): array
    {
        $query = "SELECT reason, autounbandate FROM banned_members WHERE username = :username ORDER BY bandate DESC LIMIT 1";
        $result = $this->db->queryFirst($query, [':username' => $username]);
        return $result ? $result : ['reason' => '', 'autounbandate' => null];
    }

    public function showOnlineList(): bool
    {
        // Check if at least one user is online
        $query = "SELECT COUNT(*) AS online_count FROM members WHERE lastlogin > :last";
        $last = time() - 120; // 120 seconds
        $result = $this->db->queryFirst($query, [':last' => $last]);
        return $result && $result['online_count'] > 0;
    }
}