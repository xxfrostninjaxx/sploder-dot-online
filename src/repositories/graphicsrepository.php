<?php

require_once(__DIR__ . "/../database/idatabase.php");
require_once(__DIR__ . "/igraphicsrepository.php");

class GraphicsRepository implements IGraphicsRepository
{
    private readonly IDatabase $db;

    function __construct(IDatabase $db)
    {
        $this->db = $db;
    }

    public function replaceTags(int $graphicId, array $tags): void
    {
        $this->db->execute("DELETE FROM graphic_tags WHERE g_id = :id", [
          ':id' => $graphicId
        ]);

        if (!empty($tags)) {
            $values = [];
            $params = [':id' => $graphicId];
            foreach ($tags as $index => $tag) {
                $values[] = "(:id, :tag$index)";
                $params[":tag$index"] = $tag;
            }
            $qs = "INSERT INTO graphic_tags (g_id, tag) VALUES " . implode(", ", $values);
            $this->db->execute($qs, $params);
        }
    }

    public function getUserId(int $graphicId): string
    {
        return $this->db->queryFirstColumn("SELECT userid FROM graphics WHERE id = :id", 0, [
          ':id' => $graphicId
        ]);
    }

    public function getTags($graphicId): array
    {
        return $this->db->query("SELECT tag FROM graphic_tags WHERE g_id = :id", [
          ':id' => $graphicId,
        ]);
    }

    public function trackLike(int $graphicsId, int $loggedInUserId): void
    {
        $userId = $this->db->queryFirstColumn("SELECT userid FROM graphics WHERE id=:id", 0, [
        'id' => $graphicsId
        ]);

        if ($userId !== $loggedInUserId) {
            $this->db->execute("
                INSERT INTO graphic_likes (userid, g_id) VALUES (:userid, :projid)
                ON CONFLICT DO NOTHING", [
                ':userid' => $loggedInUserId,
                ':projid' => $graphicsId,
                ]);
        }
    }
	
	public function getTotal()
	{
	    try {
	        $likesQuery = "SELECT COUNT(*) AS count FROM graphic_likes gl JOIN graphics g ON gl.g_id = g.id WHERE g.isprivate = false AND g.ispublished = true";
	        $graphicsQuery = "SELECT COUNT(*) AS count FROM graphics WHERE isprivate = false AND ispublished = true";

	        if ($this->db instanceof PDO) {
	            $likesStmt = $this->db->query($likesQuery);
	            $graphicsStmt = $this->db->query($graphicsQuery);
	            return [
	                'likes' => (int) $likesStmt->fetchColumn(),
	                'graphics' => (int) $graphicsStmt->fetchColumn()
	            ];
	        } elseif (method_exists($this->db, 'query')) {
	            $likesResult = $this->db->query($likesQuery);
	            $graphicsResult = $this->db->query($graphicsQuery);
	            return [
	                'likes' => (int) ($likesResult[0]['count'] ?? 0),
	                'graphics' => (int) ($graphicsResult[0]['count'] ?? 0)
	            ];
	        }

	        return ['likes' => 0, 'graphics' => 0];
	    } catch (Exception $e) {
	        return ['likes' => 0, 'graphics' => 0];
	    }
	}

    public function getTotalPublicGraphics(): int
    {
        $qs = "SELECT COUNT(id) FROM graphics WHERE isprivate=false AND ispublished=true";
        $total_graphics = $this->db->queryFirstColumn($qs, 0);
        return $total_graphics;
    }

    public function getPublicGraphics(int $offset = 0, int $perPage = 36): array
    {

        $queryString = '
            SELECT g.*, m.username 
            FROM graphics g
            LEFT JOIN members m ON g.userid = m.userid
            WHERE g.isprivate = false AND g.ispublished = true
            ORDER BY g.id DESC
            LIMIT :perPage OFFSET :offset';
        return $this->db->query($queryString, [
				':perPage' => $perPage,
				':offset' => $offset*$perPage
	]);
    }

    public function getGraphicTags(int $offset, int $perPage): PaginationData
    {
        return $this->db->queryPaginated(
            "SELECT DISTINCT gt.tag, COUNT(*) AS graphic_count
            FROM graphic_tags gt
            JOIN graphics g ON gt.g_id = g.id
            WHERE g.ispublished = true AND g.isprivate = false
            GROUP BY gt.tag ORDER BY graphic_count DESC, gt.tag",
            $offset,
            $perPage
        );
    }

    public function getGraphicsWithTag(string $tag, int $offset, int $perPage): PaginationData
    {
        $query = "SELECT g.*, m.username FROM graphics g
                  LEFT JOIN members m ON g.userid = m.userid
                  JOIN graphic_tags gt ON gt.g_id = g.id
                  WHERE g.isprivate = false AND g.ispublished = true AND gt.tag = :tag
                  ORDER BY g.id DESC";
        return $this->db->queryPaginated($query, $offset, $perPage, [':tag' => $tag]);
    }

    public function getTotalPublicGraphicsByUsername(string $username): int
    {
        $query = "SELECT COUNT(*) FROM graphics WHERE userid = (SELECT userid FROM members WHERE username = :username) AND isprivate = false AND ispublished = true";
        return $this->db->queryFirstColumn($query, 0, [':username' => $username]);
    }

    public function getPublicGraphicsByUsername(string $username, int $offset = 0, int $perPage = 36): array
    {
        $query = "SELECT g.id, g.userid, g.isprivate, g.ispublished, 
                         m.username, COUNT(gl.g_id) as like_count 
                  FROM graphics g
                  LEFT JOIN members m ON g.userid = m.userid
                  LEFT JOIN graphic_likes gl ON g.id = gl.g_id
                  WHERE g.userid = (SELECT userid FROM members WHERE username = :username) 
                    AND g.isprivate = false 
                    AND g.ispublished = true
                  GROUP BY g.id, g.userid, g.isprivate, g.ispublished, m.username
                  ORDER BY like_count DESC, g.id DESC
                  LIMIT :perPage OFFSET :offset";
        return $this->db->query($query, [
            ':username' => $username,
            ':perPage' => $perPage,
            ':offset' => $offset * $perPage
        ]);
    }
    public function getTotalGraphicLikesByUserId(int $userId): int
    {
        $qs = "SELECT COUNT(gl.g_id) AS total_likes
                FROM graphic_likes gl
                JOIN graphics g ON gl.g_id = g.id
                WHERE g.userid = :userid";
        $result = $this->db->queryFirst($qs, [':userid' => $userId]);
        return (int)($result['total_likes'] ?? 0);
    }

}
