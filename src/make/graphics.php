<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <?php require('../content/ruffle.php'); ?>
    <?php require('../content/swfobject.php'); ?>
    <?php require('../content/head.php'); ?>



    <script type="text/javascript">
        
        var userid = "<?php echo isset($_SESSION['username']) ? $_SESSION['userid'] : 'demo'; ?>";

        var page_start_time = (new Date()).getTime();

        function getPhotos() {

            //popUpWindow("php/uploadform.php?PHPSESSID=", Math.floor(screen.width / 2) - 275, Math.floor(screen.height / 2) -
            //    225, 550, 450);

        }


        <?php if (!isset($_SESSION['username'])) { ?>
            var flashvars = {
                PHPSESSID: "demo",
                userid: "demo",
                username: "demo",
                creationdate: "20070102003743"
            };
        <?php } else { ?>
            var flashvars = {
                PHPSESSID: "<?php echo $_SESSION['PHPSESSID']; ?>",
                userid: "<?php echo $_SESSION['userid'] ?>",
                username: "<?php echo $_SESSION['username'] ?>",
                creationdate: "<?php echo time() ?>"
            };

        <?php } ?>

        var params = {
            menu: "false",
            quality: "high",
            scale: "noscale",
            salign: "tl",
            bgcolor: "#333333",
        };

        var attributes = {
            v: "1"
        };

        swfobject.embedSWF("../swf/pixeleditor05.swf", "flashcontent", "720", "550", "10.1.53", "/swfobject/expressInstall.swf",
            flashvars, params);
        
    </script>
    <script type="text/javascript">window.rpcinfo = "Making a Graphic";</script>


</head>
<?php require('../content/addressbar.php'); ?>

<body id="creator" class="graphicseditor">
    <?php require('../content/headernavigation.php'); ?>


    <div id="page">
        <?php require('../content/subnav.php'); ?>


        <div id="content">
            <h3>Free Graphics Editor, Make Pixel Art for Games</h3>
            <div id="creatorcontainer" style="height: 550px;">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 420px;">
                        <img src="../images/graphics-editor.png" width="348" height="172" /><br /><br /><br />
                        <p>Learn how to design and draw your own graphics with this <strong>free graphics
                                editor</strong>. With this, you can build your own animated game sprites, game tiles and
                            textures (like <a href="https://www.gimp.org">gimp</a> or other programs) for use in the
                            game maker.</p>
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
        <?php require('../content/footernavigation.php'); ?>


</body>

</html>