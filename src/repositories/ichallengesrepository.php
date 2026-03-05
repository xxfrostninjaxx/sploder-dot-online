<?php

/**
 * Handles database interactions with challenges
 */
interface IChallengesRepository
{
    /**
     * Retrieves challenge data for a given game
     * @param int $gameId
     * @return array
     */
    public function getChallengeInfo(int $gameId): array|false;

    /**
     * Verify if 's' is correct
     * @param int $gameId
     * @param int $userId
     * @return bool
     */
    public function verifyIfSIsCorrect(int $gameId, int $userId): bool;

    /**
     * Add a new challenge
     * @param int $gameId
     * @param bool $mode
     * @param int $challenge
     * @param int $prize
     * @param int $winners
     * @return void
     */
    public function addChallenge(int $gameId, bool $mode, int $challenge, int $prize, int $winners);

    /**
     * Get the challenge ID for a game
     * @param int $gameId
     * @return int|null
     */
    public function getChallengeId(int $gameId): int|null;

    /**
     * Verify challenge ID and session challenge ID
     * @param int $gameId
     * @param int $challengeId
     * @param int $sessionChallengeId
     * @return bool
     */
    public function verifyChallengeId(int $gameId, int $challengeId, int $sessionChallengeId): bool;

    /**
     * Unverify a challenge
     * @param int $gameId
     * @return void
     */
    public function unverifyChallenge(int $gameId): void;

    /**
     * Get all challenges with pagination
     * @param int $offset
     * @param int $perPage
     * @return array
     */
    public function getAllChallenges(int $offset, int $perPage): array;

    /**
     * Check if the challenge creator is the owner
     * @param int $challengeId
     * @param int $userId
     * @return bool
     */
    public function checkIfChallengeCreatorIsOwner(int $challengeId, int $userId): bool;

    /**
     * Verify a challenge
     * @param int $challengeId
     * @return bool
     */
    public function verifyChallenge(int $challengeId): bool;

    /**
     * Add a winner to a challenge
     * @param int $challengeId
     * @param int $userId
     * @return bool
     */
    public function addChallengeWinner(int $challengeId, int $userId): bool;

    /**
     * Check if a user has already won a challenge
     * @param int $g_id
     * @param int $userId
     * @return bool
     */
    public function hasWonChallenge(int $g_id, int $userId): bool;

    /**
     * Get the number of challenges
     * @return int
     */
    public function getTotalChallengeCount(): int;
}