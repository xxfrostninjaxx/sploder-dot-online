<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="splodersimple.css" />

    <link rel="stylesheet" type="text/css" href="/slider/nivo-slider.css" />
    <link rel="stylesheet" type="text/css" href="/slider/sploder/style_v2p10.css" />
    <script type="text/javascript">window.rpcinfo = "Signed up";</script>
    <?php include('../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="">


    <div id="main">
        <div id="header">
            <a>
                <div id="title">
                    <h1><a title="Sploder"><img style="margin-top:-20px; height: 130px" src="/chrome/logo.png"><span
                                class="hide">Games at Sploder</span></a></h1>
                </div>
                <div id="tools"></div>
            </a>
        </div>
        <div id="page">
            <div id="content">
                <h3>Registration Successful!</h3>
                <hr>
                <p class="prompt">Registration completed successfully! You may now <a href="/accounts/login.php">log in</a>!</p>


                <div class="spacer">&nbsp;</div>
            </div>
            <div class="spacer">&nbsp;</div>
        </div>
    </div>
    <div id="bottomnav"></div>
<?php require_once(__DIR__.'/../content/versionstring.php'); ?>
</body>

</html>