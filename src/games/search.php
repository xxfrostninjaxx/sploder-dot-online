<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once(__DIR__ . '/../services/GameListRenderService.php');
require_once(__DIR__ . '/../repositories/repositorymanager.php');
require_once('../content/taglister.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
$gameTags = $gameRepository->getGameTags(0, 25);

$perPage = 12;
$offset = $_GET['o'] ?? 0;
$gameListRenderService = new GameListRenderService($gameRepository);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Searching for a Game";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="search">
    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>
        <div id="content">
            <h3>Game Search</h3>
            <p>Search for games by entering a search query below. If you wish to search for members, visit the <a href="/members/search.php">member search page</a>.
            </p>
            <form action="search.php" method="GET">
                <label for="game" style="font-size: 16px;">Enter a search term: &nbsp;</label>
                <input type="text" name="game" size="16" maxlength="40" class="biginput" 
                       value=<?php if (isset($_GET['game'])) {
                            echo json_encode($_GET['game']);
                              } else {echo '""';} ?>>
                <input type="submit" value="Search" class="postbutton">
            </form>
            <br><br>
            <div class="tagbox">
            <div id="viewpage">
                
                <?php
                if (isset($_GET['game']) && $_GET['game'] != '') {
                    $gameListRenderService->renderPartialViewForGamesSearch($_GET['game'], $offset, $perPage);
                }
                $gameListRenderService->renderPartialViewForMostPopularTags();
                ?>
                <div class="spacer">&nbsp;</div>
            </div>
        </div>
        </div>
        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>
