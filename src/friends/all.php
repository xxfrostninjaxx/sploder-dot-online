<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once(__DIR__ . "/../repositories/repositorymanager.php");
require_once(__DIR__ . "/../members/content/searchresult.php");
$searchUserName = $_GET['u'] ?? '';
$offset = $_GET['o'] ?? 0;
$perPage = 30;
$friendsRepository = RepositoryManager::get()->getFriendsRepository();
$totalFriends = $friendsRepository->getTotalFriends($_SESSION['username']);
require_once(__DIR__ . "/../services/FriendsListRenderService.php");
$friendsListRenderService = new FriendsListRenderService($friendsRepository);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <script type="text/javascript">window.rpcinfo = "Managing their Friends";</script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="">

    <?php include('../content/headernavigation.php') ?>
    
    <div id="page">
        <?php
        require_once('../services/DashboardSubnavService.php');
        $subnavService = new DashboardSubnavService();
        echo $subnavService->renderNavigationLinks($_SERVER['REQUEST_URI']);
        ?>
        <div id="content">
            
            <h3>All My Friends</h3>
            <form action="" method="GET">
                <label for="username">Find an existing friend by username: &nbsp;</label>
                <input type="text" name="u" value=<?= json_encode($searchUserName) ?? '""' ?> size="16" maxlength="16" class="" />&nbsp;&nbsp;
                <input type="submit" value="Search" class="postbutton" />
            </form>
            <?php
            if ($totalFriends !== 0) {
            ?>
            <h1><span class="tagcolor1"><?= $totalFriends ?> friend<?= $totalFriends == 1 ? "" : "s" ?></span></h1>
            
            <?php
                $result = $friendsRepository->search($_SESSION['username'], $searchUserName, $offset, $perPage);
                // Split the data as soon as bested = false
                // First list is only fir bested = true
                // Second list is for bested = false

                $bested = array_filter($result->data, fn($f) => $f['bested'] == 1);
                $accepted = array_filter($result->data, fn($f) => $f['bested'] == 0);

                echo $friendsListRenderService->renderPartialViewForFriendSearchWithActions($bested, $accepted);
                require_once(__DIR__ . "/../content/pages.php");
                addPagination($result->totalCount, $perPage, $offset);
            }
            ?>
        </div>
        <div class="spacer">&nbsp;

        </div>

        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>