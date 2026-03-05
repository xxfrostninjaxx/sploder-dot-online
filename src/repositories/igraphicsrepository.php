<?php

/**
 * Handles database interactions with graphics
 */
interface IGraphicsRepository
{
  /**
   * Replaces all tags for a specified graphic
   *
   * @param $graphicId the graphic
   * @param $tags The array of tags (strings) to replace
   * @return void
   */
    function replaceTags(int $graphicId, array $tags): void;

  /**
   * Retrieves the UserId for the specified graphic
   *
   * @param $graphicId
   * @return the user id
   */
    function getUserId(int $graphicId): string;

  /**
   * Retrieves all of the tags for a specified graphic
   *
   * @param $graphicId
   * @return the array of tags
   */
    function getTags($graphicId): array;
  /**
   * Insert a like for a specific graphics id and user
   */
    function trackLike(int $graphicsId, int $loggedInUserId): void;

  /**
   * Gets the total number of public graphics
   * 
   * @return int total number of public graphics
   */
    function getTotalPublicGraphics(): int;
  
  /**
   * Gets public graphics
   * 
   * @param $offset
   * @return array of graphics
   */
    function getPublicGraphics(int $offset, int $perPage): array;

  /**
   * Gets the total number of likes on all graphics for a specific user
   * 
   * @param int $userId
   * @return int total number of likes on all graphics
   */
    public function getTotalGraphicLikesByUserId(int $userId): int;

  /**
   * Retrieves all tags for graphics
   *
   * @param $perPage
   * @param $offset
   * @return all tags for graphics
   */
    public function getGraphicTags(int $offset, int $perPage): PaginationData;

  /**
   * Retrieves graphics with a specific tag
   * 
   * @param string $tag
   * @param int $offset
   * @param int $perPage
   * @return PaginationData
   */
    public function getGraphicsWithTag(string $tag, int $offset, int $perPage): PaginationData;

  /**
   * Gets the total number of public graphics by a user ID
   * 
   * @param string $username
   * @return int Total number of public graphics
   */
    public function getTotalPublicGraphicsByUsername(string $username): int;

  /**
   * Gets public graphics by a user ID
   * 
   * @param string $username
   * @param int $offset
   * @param int $perPage
   * @return array
   */
    public function getPublicGraphicsByUsername(string $username, int $offset = 0, int $perPage = 36): array;
}
