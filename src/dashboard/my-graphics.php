<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
include('../content/logincheck.php');
include('content/my-graphics.php');
require('../repositories/repositorymanager.php');
$graphicRepository = RepositoryManager::get()->getGraphicsRepository();
$total_likes = $graphicRepository->getTotalGraphicLikesByUserId($_SESSION['userid']);
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
        function delproj(id) {
            let text;
            if (confirm(("Are you sure you want to delete this graphic?")) == true) {
                location.href = ("../php/delete_graphic.php?id=" + id);
            } else {}
        }
    </script>
    <script type="text/javascript">window.rpcinfo = "Managing their Graphics";</script>
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
            <h3>My Graphics</h3>
            <?php if (isset($_GET['err'])) { ?>
                <div class="alert">There was an error deleting your graphic</div>
            <?php } ?>
            <p>You've made <?= $total_games ?>
                graphic<?= $total_games == 1 ? '' : 's' ?> so far,
                with a total of <?= $total_likes ?>
                like<?= $total_likes == 1 ? '' : 's' ?> so far.
                <a href="../make/graphics.php">Make
                    some graphics
                </a> now!
            </p>
            <div id="viewpage">
                <div class="set">
                    <?php
                    if ($total_games == "0") {
                        echo 'You have not made any graphics yet.<div class="spacer">&nbsp;</div>';
                    }

                    foreach ($result as $counter => $game) {
                        if ($game['id'] == null) {
                            break;
                        }
                        $counter++;
                        ?><div class="game vignette">
                            <div class="photo">
                                <a><img src="/graphics/gif/<?= $game['id'] ?>.gif" width="80" height="80" /></a>
                                <div style="text-align: center;">
                                    <div style="height:5px" class="spacer">&nbsp;</div>
                                    <?= $game['likes'] ?> like<?= $game['likes'] == 1 ? '' : 's' ?><br>
                                    <input title=" Delete" type="button" onclick="delproj(<?= $game['id'] ?>)"
                                        style="width:37px" value="Delete">&nbsp;
                                    <a href="tag-graphic.php?id=<?= $game['id'] ?>"><input title=" Tag" type="button"
                                            style="width:25px" value="Tag"></a>
                                </div>
                            </div>


                            <div class="spacer">&nbsp;</div><br>
                            <div class="spacer">&nbsp;</div><br><br>
                        </div>
                        <?php
                        if ($counter % 4 == 0) {
                            echo '<div class="spacer">&nbsp;</div>';
                        }
                    }
                    ?>
                    <div class="spacer">&nbsp;</div>
                </div>
            </div>
            <?php include('../content/pages.php');
            addPagination($total_games ?? 0,  12, $o) ?>
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
