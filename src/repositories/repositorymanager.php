<?php

// This is the main file necessary to import to access the repositories

require_once(__DIR__ . "/irepositorymanager.php");

// Require the database manager to inject the IDatabase
require_once(__DIR__ . "/../database/databasemanager.php");

// Require the implementation of the repos
require_once(__DIR__ . "/gamerepository.php");
require_once(__DIR__ . "/graphicsrepository.php");
require_once(__DIR__ . "/userrepository.php");
require_once(__DIR__ . "/awardsrepository.php");
require_once(__DIR__ . "/userrepository.php");
require_once(__DIR__ . "/contestrepository.php");
require_once(__DIR__ . "/friendsrepository.php");
require_once(__DIR__ . "/challengesrepository.php");

class RepositoryManager implements IRepositoryManager
{
    private readonly IAwardsRepository $awardsRepository;
    private readonly IContestRepository $contestRepository;
    private readonly IGameRepository $gameRepository;
    private readonly IGraphicsRepository $graphicsRepository;
    private readonly IUserRepository $userRepository;
    private readonly IFriendsRepository $friendsRepository;
    private readonly IChallengesRepository $challengesRepository;

    private function __construct(IDatabase $database)
    {
        $this->awardsRepository = new AwardsRepository($database);
        $this->contestRepository = new ContestRepository($database);
        $this->gameRepository = new GameRepository($database);
        $this->graphicsRepository = new GraphicsRepository($database);
        $this->userRepository = new UserRepository($database);
        $this->friendsRepository = new FriendsRepository($database);
        $this->challengesRepository = new ChallengesRepository($database);
    }

    public function getAwardsRepository(): IAwardsRepository
    {
        return $this->awardsRepository;
    }

    public function getContestRepository(): IContestRepository
    {
        return $this->contestRepository;
    }

    public function getGameRepository(): IGameRepository
    {
        return $this->gameRepository;
    }

    public function getGraphicsRepository(): IGraphicsRepository
    {
        return $this->graphicsRepository;
    }

    public function getUserRepository(): IUserRepository
    {
        return $this->userRepository;
    }

    public function getFriendsRepository(): IFriendsRepository
    {
        return $this->friendsRepository;
    }

    public function getChallengesRepository(): IChallengesRepository
    {
        return $this->challengesRepository;
    }

    private static IRepositoryManager|null $value = null;
    public static function get(): IRepositoryManager
    {
        if (RepositoryManager::$value == null) {
            RepositoryManager::$value = new RepositoryManager(DatabaseManager::get()->getPostgresDatabase());
        }

        return RepositoryManager::$value;
    }
}
