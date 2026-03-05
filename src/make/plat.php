<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php require('content/head.php'); ?>

    var attributes = {
    v: "2"
    };

    swfobject.embedSWF("../swf/creator2_b17.swf", "flashcontent", "860", "540", "10.0.0",
    "/swfobject/expressInstall.swf", flashvars, params);
    </script>
    <script type="text/javascript">window.rpcinfo = "Making a Platformer Game";</script>

</head>
<?php include('../content/addressbar.php'); ?>

<body id="creator" class="platformer">
    <?php include('../content/headernavigation.php'); ?>



    <div id="page">
        <?php include('../content/subnav.php'); ?>



        <div id="content">
            <h3>Platformer Game Maker</h3>

            <div id="creatorcontainer" style="width: 860px;">
                <div id="flashcontent">
                    <div style="margin: 20px auto; text-align: center; width: 425px;">
                        <img src="../images/platformer-creator.png" width="405px" height="240" /><br />
                        <p>Make your own platformer game with this game maker. Add ninjas, dragons, and other bad guys
                            and battle them with swords, guns, and other cool weapons.</p>
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