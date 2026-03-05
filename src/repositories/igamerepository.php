<?php

require_once(__DIR__ . "/../database/PaginationData.php");

/**
 * Handles database interactions with games
 */
interface IGameRepository
{
  /**
   * Inserts a view for a specified game
   *
   * @param $gameId the game to track the view
   * @param $ipAddress the ip address assosciated with the user playing the game
   * @param $userId the logged in user who is playing the game, if applicable
   */
    public function trackView(int $gameId, string $ipAddress, int|null $userId): void;

  /**
   * Returns creator userid of a given game
   */
    public function getUserId(int $gameId): string;


  /**
   * Retrieves game data for playing a game
   *
   * @param $gameId
   * @return game data associated with the game
   */
    public function getGameData(int $gameId): GameData;

    /**
     * Retrieves tags for a given game
     *
     * @param $perPage
     * @param $offset
     * @return tags associated with the game
     */
    public function getGameTags(int $offset, int $perPage): PaginationData;

    /**
     * Retrieves random games from the database
     * @return random games
     */
    public function getRandomGames(): array;
    public function getWeirdRandomGames(): array;

    /**
     * Retrieves games that are pending deletion
     * @return games pending deletion
     */
    public function getPendingDeletionGames(): array;

    /**
     * Retrieves games for a given member
     *
     * @param $userId
     * @param $perPage
     * @param $offset
     * @param $isDeleted
     * @return games
     */
    public function getPublicGamesFromUser(string $userName, int $offset, int $perPage): PaginationData;

    public function getAllGamesFromUser(string $userName, int $offset, int $perPage, bool $isDeleted): PaginationData;

    /**
     * Retrieves games for a given member based on a search parameter
     *
     * @param $userId
     * @param $perPage
     * @param $offset
     * @param $isDeleted
     * @return games
     */
    public function getGamesFromUserAndGameSearch(string $userName, string $game, int $offset, int $perPage, bool $isDeleted): PaginationData;

    /**
     * Retrieves the latest games
     *
     * @param $perPage
     * @param $offset
     * @return games
     */
    public function getGamesNewest(int $offset, int $perPage): PaginationData;

    /**
     * Retrieves the latest games with a search term
     *
     * @param $perPage
     * @param $offset
     * @return games
     */
    public function getGamesNewestByName(string $game, int $offset, int $perPage): PaginationData;

    /**
     * Retrieves the games with a specified tag
     *
     * @param $perPage
     * @param $offset
     * @return games
     */
    public function getGamesWithTag(string $tag, int $offset, int $perPage): PaginationData;

    /**
     * Retrieves the tags for a given game
     *
     * @param $perPage
     * @param $offset
     * @return games
     */
    public function getTagsFromGame(int $gameId): array;

    /**
     * Retrieves the contest winners from the database
     * @return contest winners
     */
    public function getContestWinners(int $contestIdOffset): array;

    /**
     * Retrieves games that are pending deletion
     * @param $daysOld if exceeds this many days, will delete them
     */
    public function removeOldPendingDeletionGames(int $daysOld): void;

    /**
     * Retrieves the total count of published games
     */
    public function getTotalPublishedGameCount(): int;

    /**
     * Retrieves the total count of published games
     */
    public function getTotalDeletedGameCount($userName): int;

    /**
     * Retrieves the total count of published games for a user
     */
    public function getTotalMetricsForUser(string $userName): GameMetricsForUser;

    /**
     * Retrieves basic information, title, author and SWF type of the game
     */
    public function getGameBasicInfo(int $gameId): array;

    /**
     * Checks if comments are allowed for a specific game
     *
     * @param int $gameId
     * @return bool
     */
    public function allowComment(int $gameId): bool;

    /**
     * Publish a game
     * 
     * @param int $id of the game
     * @param bool $private game
     * @param string $comments are enabled
     * @return void
     */
    public function publishGame(int $id, bool $private, string $comments): void;

    /**
     * Set featured status of a game
     * 
     * @param int $id of the game
     * @param bool $feature true to feature, false to unfeature
     * @param int $editorUserId user ID of the editor featuring/unfeaturing the game
     * @return void
     */
    public function setFeaturedStatus(int $id, bool $feature, int $editorUserId): void;

    /**
     * Get featured status of a game
     * 
     * @param int $id of the game
     * @return bool true if featured, false otherwise
     */
    public function getFeaturedStatus(int $id): bool;

    /**
     * Get review data for a game by a reviewer
     * 
     * @param int $userId
     * @param int $gameId
     * @return array review data or empty array if no review exists
     */
    public function getReviewData(int $userId, int $gameId): array;

    /**
     * Save review data for a game by a reviewer
     * 
     * @param int $userId
     * @param int $gameId
     * @param string $title
     * @param string $review
     * @param bool $isPublished
     * @return void
     */
    public function saveReview(int $userId, int $gameId, string $title, string $review, bool $isPublished): void;

    /**
     * Get published reviews for a game
     * 
     * @param int $perPage
     * @param int $offset
     * @return PaginationData of reviews
     */
    public function getPublicReviews(int $offset, int $perPage): PaginationData;

    /**
     * Get reviewer user IDs who have published reviews for a game
     * 
     * @param int $gameId
     * @return array of user IDs
     */
    public function getReviewsForGame(int $gameId): array;

    /**
     * Get reviews by username
     * 
     * @param string $username
     * @return array of reviews
     */
    public function getReviewsByUsername(string $username): array;

    /**
     * Get all reviews by username
     * 
     * @param string $username
     * @param int $offset
     * @param int $perPage
     * @return PaginationData of reviews
     */
    public function getAllReviewsByUsername(string $username, int $offset, int $perPage): PaginationData;

    /**
     * Delete a review by a reviewer for a game
     * 
     * @param int $userId
     * @param int $gameId
     * @return void
     */
    public function deleteReview(int $userId, int $gameId): void;

    /**
     * Check if a user has reviewed a game
     * 
     * @param int $userId
     * @param int $gameId
     * @return bool true if reviewed, false otherwise
     */
    public function hasUserReviewedGame(int $userId, int $gameId): bool;

    /**
     * Get "S" from review ID
     * 
     * @param int $reviewId
     * @return string "S" parameter for the review
     */
    public function getGameSFromReviewId(int $reviewId): string;
}

class GameMetricsForUser
{
    public readonly int $totalViews;
    public readonly int $totalGames;

    public function __construct(int $totalViews, int $totalGames)
    {
        $this->totalViews = $totalViews;
        $this->totalGames = $totalGames;
    }
}

class GameData
{
    public readonly string $author;
    public readonly string $difficulty;
    public readonly float $avgScore;

    public function __construct(string $author, string $difficulty, float $avgScore)
    {
        $this->author = $author;
        $this->difficulty = $difficulty;
        $this->avgScore = $avgScore;
    }
}
