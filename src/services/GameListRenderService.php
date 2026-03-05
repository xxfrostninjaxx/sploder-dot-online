<?php

require_once(__DIR__ . '/../content/pages.php');
require_once(__DIR__ . '/../content/timeelapsed.php');

class GameListRenderService
{
    private readonly IGameRepository $gameRepository;

    public function __construct(IGameRepository $gameRepository)
    {
        $this->gameRepository = $gameRepository;
    }

    private function renderPartialViewForGames(
        array $games,
        string $noGamesFoundMessage,
        bool $includeStyleWidth,
        bool $includeDelete,
        bool $includeRestore,
        bool $includeBoost,
        bool $includeChallenge,
        bool $includeUsername,
        bool $fixSidebar
    ): void {
        if (count($games) <= 0) {
            echo "<div class=\"set\"><p class=\"prompt\">$noGamesFoundMessage</p></div>";
            echo $fixSidebar ? '<div>' : '';
            return;
        }
        $anyModification = $includeDelete || $includeRestore || $includeBoost || $includeChallenge;
        $counter = -1;
        ?>
            <div id="viewpage">
            <div <?= ($includeStyleWidth == true) ? 'style="width:915px;"' : '' ?> class="set">
                    <?php foreach ($games as $game) :
                        $counter = $counter + 1;
                        $id = $game['g_id'];
                        $reason = $game['reason'] ?? null;
                        $swf = $game['g_swf'];
                        $title = htmlspecialchars($game['title']);
                        $userId = $game['user_id'];
                        $author = $game['author'];
                        $date = $game['first_published_date'] ?? $game['first_created_date'];
                        $contest_game = $game['contest_game'] ?? false;
                        $views = $game['views'];
                        $lastModified = $game['date'] ?? null;
                        $gameDate = date('m&\m\i\d\d\o\t;d&\m\i\d\d\o\t;y', strtotime($date));
                        $avgRating = $game['avg_rating'] ?? 0;
                        $starUrl = "/chrome/rating" . (round($avgRating * 10 / 5) * 5) . ".gif";
                        $includeTotalVotes = isset($game['total_votes']);
                        $totalVotes = $game['total_votes'] ?? 0;
                        $isPublished = (bool)($game['ispublished'] ?? 1);
                        ?>
                    <div class="game <?= ($contest_game == true) ? 'boosted_game' : '' ?>">
                        <div class="photo">
                            <a
                                href="/games/play.php?&s=<?= $userId ?>_<?= $id ?>">
                                <img src="/users/user<?= $userId ?>/images/proj<?= $id ?>/thumbnail.png"
                                    width="80" height="80" />
                            </a>
                        </div>
                        <div class="game-details">
                            <p class="gamedate"><?= $gameDate ?></p>
                            <h4>
                                <a
                                    href="/games/play.php?&s=<?= $userId ?>_<?= $id ?>"><?= urldecode($title) ?>
                                </a>
                            </h4>
                            <?php if ($includeUsername) { ?>
                                <h5>
                                    <a href="../../members/index.php?u=<?= $author ?>"><?= $author ?></a>
                                </h5>
                            <?php } ?>
                            <?php
                            if ($includeTotalVotes) {
                                ?>
                            <p class="gamevote">
                                <img src="<?= $starUrl ?>" width="64" height="12" border="0" alt="'<?= $avgRating ?>' stars"/>
                                <?= $totalVotes ?> vote<?= ($totalVotes == 1 ? '' : 's') ?>
                            </p>
                            <?php } ?>
                            <?php if ($contest_game) { ?>
                                <p class="boostleader"><a href="/games/contest.php">Contest Winner!</a></p>
                            <?php } ?>
                            <p class="gameviews">
                                <?php
                                    if ($isPublished) {
                                ?>
                                    <?= $views ?> view<?= ($views == 1) ? '' : 's' ?>
                                <?php
                                    } else {
                                        echo '(Unpublished)';
                                    }
                                ?>
                            </p>
                            <?php if (isset($lastModified)) { ?>
                                <p class="gameviews">Edited: <?= time_elapsed_string($lastModified, false) ?></p>
                            <?php } ?>
                            <?php if ($anyModification) { ?>
                                <div class="game-buttons">
                                    <?php if ($includeDelete) { ?>
                                        <input title="Delete" type="button"
                                            onclick='delproj(<?= $id ?>,<?= json_encode(urldecode($title)) ?>)'
                                            type="button" value="Delete">&nbsp;
                                    <?php } ?>
                                    <?php if ($includeRestore) { ?>
                                        <input title="Restore" type="button" class="boost_button"
                                            onclick='resproj(<?= $id ?>,<?= json_encode(urldecode($title)) ?>)'
                                            style="" value="Restore">&nbsp;
                                    <?php } ?>
                                    <?php if ($includeBoost) { ?>
                                        <input title="Boost" type="button" class="boost_button" value="Boost">
                                    <?php } ?>
                                    <?php if ($includeChallenge) { ?>
                                        <?php if ($includeBoost) {
                                            echo '&nbsp;';
                                        } ?>
                                        <?php
                                        if(!isset($game['challenge_id']) && ($game['isprivate'] == false && $game['ispublished'] == true)){ ?>
                                            <a href='/games/challenges.php?s=<?= $_SESSION['userid'] .'_'. $id ?>'><input title="Challenge" type="button" class="challenge_button" value="Challenge"></a>
                                        <?php } ?>
                                    <?php } ?>

                                
                                </div>
                            <?php } ?>
                            <div class="spacer">&nbsp;</div>
                            <?php if ($reason !== null) {
                                echo nl2br(htmlspecialchars($reason));
                            }?>
                        </div>
                    </div>
                        <?= ($counter % 2 == 1) ? '<div class="spacer">&nbsp;</div>' : "" ?>
                    <?php endforeach; ?>
                    <div class="spacer">&nbsp;</div>
                </div>
            <?= $fixSidebar ? '' : '</div>' ?>
            <div class="spacer">&nbsp;</div>
        <?php
    }

    public function renderPartialViewForMostPopularTags(): void
    {
        require_once(__DIR__ . '/../content/taglister.php');
        echo '<div class="tagbox"><p class="tags"><strong>Most Popular Tags: </strong>';
        echo displayTags($this->gameRepository->getGameTags(0, 25)->data, true);
        echo '<p>Learn more about <a href="/games/tags.php">tags</a>.</p>';
        echo '</p></div>';
    }

    public function renderPartialViewForPendingDeletion(int $daysOldToDelete): void
    {
        $this->gameRepository->removeOldPendingDeletionGames($daysOldToDelete);
        $games = $this->gameRepository->getPendingDeletionGames();
        $this->renderPartialViewForGames(
            $games,
            "No games pending deletion",
            includeStyleWidth: true,
            includeDelete: true,
            includeRestore: false,
            includeBoost: false,
            includeChallenge: false,
            includeUsername: true,
            fixSidebar: false
        );
    }

    public function renderPartialViewForUser(string $userName, int $offset, int $perPage): void
    {
        $games = $this->gameRepository->getPublicGamesFromUser($userName, $offset, $perPage);
        $this->renderPartialViewForGames(
            $games->data,
            "No games found!",
            includeStyleWidth: false,
            includeDelete: false,
            includeRestore: false,
            includeBoost: false,
            includeChallenge: false,
            includeUsername: false,
            fixSidebar: true
        );
        addPagination($games->totalCount, $perPage, $offset);
    }

    public function renderPartialViewForMyGamesUser(string $userName, int $offset, int $perPage, bool $isDeleted): void
    {
        $games = $this->gameRepository->getAllGamesFromUser($userName, $offset, $perPage, $isDeleted);
        $this->renderPartialViewForGames(
            $games->data,
            'You have not made any games yet.',
            includeStyleWidth: false,
            includeDelete: true,
            includeRestore: $isDeleted,
            // Boost/Challenge do not currently work, re-enable after implementation
            includeBoost: false,
            includeChallenge: true,
            includeUsername: false,
            fixSidebar: false
        );
        addPagination($games->totalCount, $perPage, $offset);
    }

    public function renderPartialViewForMyGamesUserAndGame(string $userName, string $game, int $offset, int $perPage, bool $isDeleted): void
    {
        $games = $this->gameRepository->getGamesFromUserAndGameSearch($userName, $game, $offset, $perPage, $isDeleted);
        $this->renderPartialViewForGames(
            $games->data,
            'This game was not found.',
            includeStyleWidth: false,
            includeDelete: true,
            includeRestore: $isDeleted,
            // Boost/Challenge do not currently work, re-enable after implementation
            includeBoost: false,
            includeChallenge: false,
            includeUsername: false,
            fixSidebar: false
        );
        addPagination($games->totalCount, $perPage, $offset);
    }

    public function renderPartialViewForNewestGames(int $offset, int $perPage): void
    {
        $games = $this->gameRepository->getGamesNewest($offset, $perPage);
        $this->renderPartialViewForGames(
            $games->data,
            "No games found!",
            includeStyleWidth: false,
            includeDelete: false,
            includeRestore: false,
            includeBoost: false,
            includeChallenge: false,
            includeUsername: true,
            fixSidebar: false
        );
        addPagination($games->totalCount, $perPage, $offset);
        $this->renderPartialViewForMostPopularTags();
    }
    public function renderPartialViewForGamesWithTag(string $tag, int $offset, int $perPage): void
    {
        $games = $this->gameRepository->getGamesWithTag($tag, $offset, $perPage);
        $this->renderPartialViewForGames(
            $games->data,
            "No games found!",
            includeStyleWidth: false,
            includeDelete: false,
            includeRestore: false,
            includeBoost: false,
            includeChallenge: false,
            includeUsername: true,
            fixSidebar: true
        );
        addPagination($games->totalCount, $perPage, $offset);
        $this->renderPartialViewForMostPopularTags();
    }

    public function renderPartialViewForGamesSearch(string $game, int $offset, int $perPage): void
    {
        $games = $this->gameRepository->getGamesNewestByName($game, $offset, $perPage);
        $this->renderPartialViewForGames(
            $games->data,
            "No games found!",
            includeStyleWidth: false,
            includeDelete: false,
            includeRestore: false,
            includeBoost: false,
            includeChallenge: false,
            includeUsername: true,
            fixSidebar: false
        );
        addPagination($games->totalCount, $perPage, $offset);
    }

    public function renderPartialViewForFeaturedGames(int $offset, int $perPage): void
    {
        $games = $this->gameRepository->getFeaturedGames($offset, $perPage);
        $this->renderPartialViewForGames(
            $games->data,
            "No featured games found!",
            includeStyleWidth: false,
            includeDelete: false,
            includeRestore: false,
            includeBoost: false,
            includeChallenge: false,
            includeUsername: true,
            fixSidebar: false
        );
        addPagination($games->totalCount, $perPage, $offset);
    }
}
