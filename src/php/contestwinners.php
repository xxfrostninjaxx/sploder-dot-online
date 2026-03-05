<?php
require_once '../content/initialize.php';

require('../repositories/repositorymanager.php');
require('../services/GameFeedService.php');

$id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT) ?? 0;

$gameRepository = RepositoryManager::get()->getGameRepository();

$gameFeed = new GameFeedService($gameRepository);

echo $gameFeed->generateFeedForContestWinners($id);
