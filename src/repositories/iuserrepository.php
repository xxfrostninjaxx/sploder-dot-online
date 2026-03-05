<?php

/**
 * Handles database interactions with users
 */
interface IUserRepository
{
  /**
   * Search for similar users by userName
   *
   * @param $userName
   * @param $limit
   * @return the array of search values
   */
    function search(string $userName, int $limit = 180);

    /**
     * Get top 90 members which have the most views per day
     * 
     * @return array of top users
     */
    function getTopMembers();

    /**
     * Get 100 members with offset
     * 
     * @param $offset
     * @return array of users
     */
    function getMembers(int $offset);

    /**
     * Get total number of members
     * 
     * @return int total number of members
     */
    function getTotalNumberOfMembers(): int;

    /**
     * Get online members
     * 
     * @return array of online members
     */
    function getOnlineMembers(): array;

    /**
     * Get level of user by user ID
     * 
     * @param $userId
     * @return int level of user
     */
    function getLevelByUserId(int $userId);

    /**
     * Save event data
     * 
     * @param $s
     * @param $e
     * @param $g
     * @return void
     */
    function saveEvent(string $s, string $e, string $g);

    /**
     * Get user ID from username
     * 
     * @param $username
     * @return int user ID or -1 if not found
     */
    function getUserIdFromUsername(string $username): int;

    /**
     * Checks if a user is isolated
     * 
     * @param $username
     * @return bool true if isolated, false otherwise
     */
    function isIsolated(string $username): bool;

    /**
     * Set isolation status for a user
     * 
     * @param $username
     * @param $isolate
     * @return void
     */
    function setIsolation(string $username, bool $isolate): void;

    /**
     * Add boost points to a user
     * 
     * @param $userId
     * @param $points
     */
    function addBoostPoints(int $userId, int $points): void;

    /**
     * Remove boost points from a user
     * 
     * @param $userId
     * @param $points
     */
    function removeBoostPoints(int $userId, int $points): void;

    /**
     * Get boost points of a user
     * 
     * @param $userId
     * @return int boost points of user
     */
    function getBoostPoints(int $userId): int;

    /**
     * Get user permissions by username
     * 
     * @param $username
     * @return string permissions of user
     */
    function getUserPerms(string $username): string;

    /**
     * Set user permissions by username
     * 
     * @param $username
     * @param $perms
     * @return bool true on success, false on failure
     */
    function setUserPerms(string $username, string $perms): bool;

    /**
     * Get ban reason of user by username
     * 
     * @param $username
     * @return array ban info of user
     */
    function getBanInfo(string $username): array;

    /**
     * Show online members list
     * 
     * @return bool true if online members exist, false otherwise
     */
    function showOnlineList(): bool;
}
