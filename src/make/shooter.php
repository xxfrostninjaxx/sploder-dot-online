<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php require('../content/ruffle.php'); ?>
    <?php require('content/head.php'); ?>

    var attributes = {
    v: "1"
    };

    swfobject.embedSWF("../swf/creator1_b01.swf", "flashcontent", "860", "540", "8", "/swfobject/expressInstall.swf",
    flashvars, params);

    </script>
    <script type="text/javascript">window.rpcinfo = "Making a Shooter Game";</script>
</head>

<body id="creator" class="shooter">

    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Make My Own Shooter Game</h3>



            <div id="creatorcontainer" style="width: 860px;">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 420px;">
                        <p>Make your own space shooter game with this game maker. Add ships, robots, powerups and
                            fight with your ray gun, mortars, mines and bots.</p>
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