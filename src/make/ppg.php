<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php require('content/head.php'); ?>

    var attributes = {
    v: "1"
    };

    swfobject.embedSWF("../swf/creator5_b21.swf", "flashcontent", "860", "600", "10.2", "/swfobject/expressInstall.swf",
    flashvars, params);

    </script>
    <script type="text/javascript">window.rpcinfo = "Making a Physics Puzzle Game";</script>



</head>
<?php include('../content/addressbar.php'); ?>

<body id="creator" class="physics">

    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Physics Puzzle Game Maker</h3>
            <div id="creatorcontainer" style="height: 600px; width: 860px">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 420px;">
                        <img src="../images/physics-puzzle-maker.png" width="348" height="172" /><br /><br /><br />
                        <p>Make your own physics puzzle game with this game maker. Build machines, robots, Rube
                            Goldberg devices, and unique games with this game maker.</p>
                        <?php include('../content/noflash.php') ?>
                    </div>
                </div>
            </div>

            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>