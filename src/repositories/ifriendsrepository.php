<?php

/**
 * Handles database interactions with friends
 */
interface IFriendsRepository
{
    /**
     * Gets the number of unviewed friends
     * 
     * @param $userId the user to check for friends
     * @param $isViewed whether the friend request has been viewed
     * @return the number of friends
     */
    public function getFriendRequestCount(int $userId, bool $isViewed): int;

    /**
     * Views all friend requests for a user
     */
    public function setAllFriendsAsViewed(int $userId): void;

    /**
     * Checks if two users are already friends
     * 
     * @param $sender the sender of the friend request
     * @param $receiver the receiver of the friend request
     * @return boolean true if they are friends, false otherwise
     */
    public function alreadyFriends(string $sender, string $receiver): bool;

    /**
     * Gets the total number of friends for a user
     * 
     * @param $username the username to check
     * @return int the total number of friends
     */
    public function getTotalFriends(string $username): int;

    /**
     * Gets the bested friends for a user, ordered by most recent first
     * 
     * @param string $username The username to get bested friends for
     * @param int $limit Maximum number of friends to return
     * @return array Array of friend records containing user1 and user2 fields
     */
    public function getBestedFriends(string $username, int $limit = 30): array;

    /**
     * Gets the regular (non-bested) friends for a user, ordered by most recent first
     * 
     * @param string $username The username to get friends for
     * @param int $limit Maximum number of friends to return
     * @return array Array of friend records containing user1 and user2 fields
     */
    public function getAcceptedFriends(string $username, int $limit): array;

    /**
     * Search for a friend by username
     * 
     * @param string $username The username who is searching
     * @param string $search The username to search for
     * @param int $page The page number to retrieve
     * @param int $perPage The number of results per page
     * @return array of friend records matching the search criteria
     */
    public function search(string $username, string $search, int $page, int $perPage): PaginationData;
}
