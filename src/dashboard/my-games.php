<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require_once('../services/GameListRenderService.php');
require_once('../repositories/repositorymanager.php');
require_once('../content/logincheck.php');
require_once('../database/connect.php');

$db = getDatabase();
$gameRepository = RepositoryManager::get()->getGameRepository();
$gameListRenderService = new GameListRenderService($gameRepository);

$username = $_SESSION['username'];
$totalMetrics = $gameRepository->getTotalMetricsForUser($username);
$currentPage = isset($_GET['o']) ? (int)$_GET['o'] : 0;

if (isset($_GET['game']) && $_GET['game'] == null) {
    unset($_GET['game']);
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
    <link href="css/css.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p3.css">
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="../slider/nivo-slider.css" />
    <link rel="stylesheet" type="text/css" href="../css/inline_help.css">
    <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
    <link rel="stylesheet" type="text/css" href="./css/notifications.css">
    <style media="screen" type="text/css">
        #swfhttpobj {
            visibility: hidden
        }
    </style>
    <?php include('../content/onlinechecker.php'); ?>
    <script>
        function delproj(id, title) {
            let text;
            if (confirm(("Are you sure you want to delete " + title)) == true) {
                location.href = ("../php/delete.php?id=" + id);
            } else {}
        }
    </script>
    <script type="text/javascript">window.rpcinfo = "Managing their Games";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="" onload="doLoad();">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php
        require_once('../services/DashboardSubnavService.php');
        $subnavService = new DashboardSubnavService();
        echo $subnavService->renderNavigationLinks($_SERVER['REQUEST_URI']);
        ?>
        <div id="content">
            <h3>My Games</h3>
            <p>You've made <?= $totalMetrics->totalGames ?> games, with a total of <?= $totalMetrics->totalViews ?> views so far.
            <form action="my-games.php" method="GET"><label for="title">Search by title: &nbsp;</label>
                <input
                    style="width:98.5%;height:26px" placeholder="My awesome game" value=<?php if (isset($_GET['game'])) {
                                                                                                echo json_encode($_GET['game']);
                                                                                         } else {echo '""';} ?> class="urlthing"
                    type="text" id="game" name="game" autocomplete="off" autocorrect="off" autocapitalize="off"
                    spellcheck="false" maxlength="100" /><br><br><br>
            </form>
                <?php
                $perPage = 12;
                if (isset($_GET['game'])) {
                    $gameListRenderService->renderPartialViewForMyGamesUserAndGame($username, $_GET['game'], $currentPage, $perPage, false);
                } else {
                    $gameListRenderService->renderPartialViewForMyGamesUser($username, $currentPage, $perPage, false);
                }
                ?>

            <div class="promo">Lost a game?<br><small><small>If you accidentally deleted a game, we may be able to
                        restore it. You can request to have it restored <a href="trash.php">here</a></small></small>
            </div>
        </div>
        <div id="sidebar">
            <!-- TODO: <h1>GAME BUZZ INCOMPLETE</h1> -->
            <br>
            <br>
            <br>
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>
