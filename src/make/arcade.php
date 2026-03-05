<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php require('content/head.php'); ?>

    function preventDefault(e) {
    e = e || window.event;
    if (e.preventDefault)
    e.preventDefault();
    e.returnValue = false;
    }

    function wheel(e) {
    preventDefault(e);
    }

    function disable_scroll() {
    if (window.addEventListener) {
    window.addEventListener("DOMMouseScroll", wheel, false);
    }
    window.onmousewheel = document.onmousewheel = wheel;
    }

    function enable_scroll() {
    if (window.removeEventListener) {
    window.removeEventListener("DOMMouseScroll", wheel, false);
    }
    window.onmousewheel = document.onmousewheel = null;
    }

    var current_pubkey = "";

    function tryPubMovie (pubkey, size) {
    current_pubkey = pubkey;
    playPubMovie(pubkey, size);
    setClass("launchprompt","shown");
    }

    function relaunchPubMovie () {
    playPubMovie(current_pubkey, 480);
    setClass("launchprompt","hidden");
    }

    var attributes = {
    v: "1"
    };

    // Make the flashcontent think that there is always a mouse cursor inside the window
    // This is done by blocking events of the mouse outside the flashwindow to be sent to the flashwindow

    function blockMouseEvents(e) {
    var flashcontent = document.getElementById("flashcontent");
    if (!flashcontent) return;

    var flashcontentRect = flashcontent.getBoundingClientRect();
    var mouseX = e.clientX;
    var mouseY = e.clientY;

    // Block only if the mouse is outside flashcontent
    if (mouseX < flashcontentRect.left || mouseX> flashcontentRect.right ||
        mouseY < flashcontentRect.top || mouseY> flashcontentRect.bottom) {

        // Check if the event target is not a form control, link, or interactive element
        var interactiveTags = ['A', 'BUTTON', 'INPUT', 'SELECT', 'TEXTAREA'];
        if (!interactiveTags.includes(e.target.tagName)) {
            e.preventDefault();
            }
        }
    }

    document.addEventListener("mousedown", blockMouseEvents, true);
    document.addEventListener("mouseup", blockMouseEvents, true);

    swfobject.embedSWF("/swf/creator7preloader2.swf", "flashcontent", "860", "626", "10.2.152",
    "/swfobject/expressInstall.swf", flashvars, params);

</script>
<script type="text/javascript">window.rpcinfo = "Making an Arcade Game";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="creator" class="arcade">
    <?php include('../content/headernavigation.php'); ?>


    <div id="page">
        <?php include('../content/subnav.php'); ?>

        <div id="content">
            <h3>Retro Arcade Game Maker</h3>
            <div style="border: 1px solid #999; color: #ccc; padding: 6px; margin: 0 19px 10px 19px; font-size: 11px; background: #660066"
                align="center" id="launchprompt" class="hidden">Playing published game. If you are blocking pop-ups,
                click <a href="#" onclick="relaunchPubMovie();">play game now</a>.</div>
            <div id="creatorcontainer" style="width: 860px; height: 626px;">
                <div id="flashcontent">
                    <div style="margin: 40px auto; text-align: center; width: 600px;">
                        <br /><br />

                        <img src="../images/retro-arcade-game-maker2.gif" width="420" height="260" /><br /><br />


                        <br />

                        <p style="width: 420px; margin: auto;">Make your own 8-bit retro arcade game with this game
                            maker. Build fun platformers, RPG stories, boss-battles, and unique games with this game
                            maker.</p>
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