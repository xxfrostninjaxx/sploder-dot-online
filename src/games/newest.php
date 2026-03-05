<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once('../services/GameListRenderService.php');
require_once('../repositories/repositorymanager.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
$gameListRenderService = new GameListRenderService($gameRepository);
$perPage = 12;
$offset = $_GET['o'] ?? 0;
$total = $gameRepository->getTotalPublishedGameCount();
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
    <script type="text/javascript">window.rpcinfo = "Viewing all Games";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="newest">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Everyone's Games</h3>
            <p>This is a list of the newest games Sploder Revival members have created. Play them, have fun, and make
                sure you
                vote for your favorites! To see
                the first games made on Sploder Revival, go to the <a
                    href="?o=<?php echo max(0, floor(($total - 1) / $perPage)); ?>">end of
                    the
                    list</a>.
            </p>
            <?php
            $gameListRenderService->renderPartialViewForNewestGames($offset, $perPage);
            ?>
        </div>
        <div class="spacer">&nbsp;</div>
    
    <div id="sidebar">
        <br /><br /><br />
        <div class="spacer">&nbsp;</div>
    </div>
    <div class="spacer">&nbsp;</div>
    <?php include('../content/footernavigation.php') ?>
</body>

</html>
