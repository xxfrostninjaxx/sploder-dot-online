<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once('../repositories/repositorymanager.php');
$graphicsRepository = RepositoryManager::get()->getGraphicsRepository();
$stats = $graphicsRepository->getTotal();

$perPage = 36;
require_once('../services/GraphicListRenderService.php');
$graphicListRenderService = new GraphicListRenderService($graphicsRepository);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
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
        function delproj(id) {
            let text;
            if (confirm(("Are you sure you want to delete this graphic?")) == true) {
                location.href = ("../php/delete_graphic.php?id=" + id);
            } else {}
        }
    </script>
    <script type="text/javascript">window.rpcinfo = "Viewing all Graphics";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="graphics" onload="doLoad();">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
    <?php include('../content/subnav.php'); ?>
    <div id="content">
    <h3>Game Graphics</h3><h4>What are these?</h4>
    <p>These are all of the graphics created by Sploder Revival members.
    You can use these graphics in the <a href="/make/ppg.php">Physics Puzzle Maker</a> or in 
    the <a href="/make/plat.php">Platformer Game Creator</a>.
    You can also <a href="/make/graphics.php">create your own graphics</a> using the online graphics editor.
    All graphics should also be <a href="/graphics/tags.php">tagged</a> to make them easy to find!</p>
    <p>There <?= $stats['graphics'] == 1 ? 'is' : 'are' ?> <?= $stats['graphics'] ?> graphic<?= $stats['graphics'] == 1 ? '' : 's' ?> so far with <?= $stats['likes'] ?> like<?= $stats['likes'] == 1 ? '' : 's' ?>.</p>
    <?php
        // Render the graphics list and pagination using the service
        $graphicListRenderService->renderPartialViewForPublicGraphics($_GET['o'] ?? 0, $perPage, $stats['graphics']);
    ?>
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
