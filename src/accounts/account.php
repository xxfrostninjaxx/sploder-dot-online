<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php include('../content/logincheck.php') ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="splodersimple.css" />


    <?php include('../content/onlinechecker.php'); ?>

    <script type="text/javascript">window.rpcinfo = "Viewing Account Information";</script>
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>





</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="">

    <div id="main">
        <div id="header">
            <a href="/">
                <div id="title">
                    <h1><a href="/" title="Sploder"><img style="margin-top:-20px; height: 130px"
                                src="/chrome/logo.png"><span class="hide">Games at Sploder</span></a></h1>
                </div>
                <div id="tools"></div>
            </a>
        </div>
        <div id="page">
            <div id="content">
                <div id="new_status" class="get_started">
                    <h3>My Sploder</h3>
                    <p>You are logged in as <?= $_SESSION['username'] ?>. Where do you want to go next?</p>
                    <ul class="actions">
                        <li>
                            <a href="/">Proceed to your dashboard &raquo;</a>
                            <p>When logged in, the Sploder Revival home page will display your dashboard.</p>
                        </li>
                        <li>
                            <a href="/make/index.php">Make your own game &raquo;</a>
                            <p>Make your own games for your friends to play.</p>
                        </li>
                        <li>
                            <a href="/dashboard/profile-edit.php">Edit your profile &raquo;</a>
                            <p>Change your public profile settings and details.</p>
                        </li>
                        <li>
                            <a target="_blank" href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>/">Join the Discord Server &raquo;</a>
                            <p>If you're 13 or older, you can join separately and participate in the discord server.</p>
                        </li>
                        <li>
                            <a target="_blank" href="<?= getenv('FORUMS_URL') ?>">Visit the Community Forums &raquo;</a>
                            <p>If you're 13 or older, you can join separately and participate in the forums.</p>
                        </li>
                        <li>
                            <a href="/friends/index.php">Find friends &raquo;</a>
                            <p>Already know people on Sploder Revival? Search for them here.</p>
                        </li>
                        <li>
                            <a href="/games/featured.php">Play some games &raquo;</a>
                            <p>Start playing and rating other people's games.</p>
                        </li>
                    </ul>

                </div>

                <!--{LATESTGAMES}--><br style="clear: both;" />
            </div>
        </div>
    </div>
    <div id="bottomnav">
        <ul>
            <li><a href="/legal/termsofservice.php">Terms of Service</a></li>
            <li><a href="/credits.php">Credits</a></li>
            <li><a href="/legal/privacypolicy.php">Privacy Policy</a></li>
        </ul>
    </div>


<?php require_once(__DIR__.'/../content/versionstring.php'); ?>

</body>

</html>