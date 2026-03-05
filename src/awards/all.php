<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require_once('../content/logincheck.php');
require_once('../database/connect.php');
require_once('php/functions.php');
require_once('../repositories/repositorymanager.php');
require_once('../services/AwardsListRenderService.php');
require_once('../content/pages.php');

$userRepository = RepositoryManager::get()->getUserRepository();
$awardsRepository = RepositoryManager::get()->getAwardsRepository();
$awardsService = new AwardsListRenderService($awardsRepository);
$level = $userRepository->getLevelByUserId($_SESSION['userid']);
if ($level < 10) {
    header("Location: ../index.php");
    die();
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="css/friends2.css" />
    <link rel="stylesheet" type="text/css" href="css/awards.css" />
    <style media="screen" type="text/css">
        #swfhttpobj {
            visibility: hidden
        }
    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime();
    </script>
    <script type="text/javascript">window.rpcinfo = "Viewing all Awards";</script>

    <link href="/css/members.css" rel="stylesheet" type="text/css" />
    <style>
        #layers_mini {
            position: relative;
            /* Ensure the container is positioned relatively */
            height: 192px;
            /* Set a height for the container */
        }

        .layer_mini {
            position: absolute;
            /* Position the layers absolutely within the container */
            top: 0;
            left: 0;
            background-color: transparent;
            /* Ensure the background color is transparent */
            width: 64px;
            height: 64px;
            margin-left: 16px;
            margin-top: 16px;
            /* Scale the layers */

        }

        .shine {
            overflow: hidden;

            width: 96px;
            height: 96px;
            background-image: url('chrome/award_shine_96.gif');

        }

        .award_text {
            margin-top: -35px;
            margin-left: 60px;
            display: flex;
        }

        .award_text dt div {
            display: inline;
            /* Ensure nested div is inline */
        }

        .award_text dl {
            margin: 0;
            padding: 0;

        }

        div.award_option span {
            display: block;
            position: absolute;
            right: 10px;
            top: 10px;
            text-align: right;
            color: #666;
        }

        div.award_option a {
            position: relative;
            color: #999;
            font-weight: normal;
            z-index: 1;
        }
    </style>
    <?php include('../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="friendsmanager" class="friend" onload="doLoad();">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php
        require_once('../services/DashboardSubnavService.php');
        $subnavService = new DashboardSubnavService();
        echo $subnavService->renderNavigationLinks($_SERVER['REQUEST_URI']);
        ?>
        <div id="content">
            <h3>All My Awards</h3>
            <?php
            $perPage = 50;
            $offset = isset($_GET['o']) ? intval($_GET['o']) : 0;
            
            $total = $awardsRepository->getAwardCount($_SESSION['username']);
            $awards = $awardsRepository->getAwardsPage($_SESSION['username'], $offset, $perPage);
            ?>

            <h5><big><?= $total ?></big>&nbsp;Award<?= $total == 1 ? '' : 's' ?></h5>

            <?php 
            echo $awardsService->renderAwardsListWithPagination($awards, $offset, $perPage, $total);
            ?>
            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">


        </div>
        <div class="spacer">&nbsp;</div><?php include('../content/footernavigation.php') ?>
</body>

</html>
