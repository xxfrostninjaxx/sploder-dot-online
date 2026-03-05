<?php
require_once 'content/initialize.php';
require(__DIR__.'/content/disablemobile.php'); ?>
<?php session_start() ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/slider/nivo-slider.css" />
    <link rel="stylesheet" type="text/css" href="/slider/sploder/style_v2p10.css" />
    <style>
        .at-symbol {
            font-family: 'Calibri';
        }
    </style>
    <script type="text/javascript">window.rpcinfo = "Viewing the Credits";</script>
    <?php include('content/onlinechecker.php'); ?>
</head>
<?php include('content/addressbar.php'); ?>

<body id="everyones" class="docs">
    <?php include('content/headernavigation.php'); ?>
    <div id="page">
        <?php include('content/subnav.php'); ?>

        <div id="content" style="width:92%">
            <h3>Credits</h3>
            <p>This project would not have been possible without:</p>


                <hr>
            <h2>Geoff: Our lord and saviour; owner of sploder.com
            </h2>
            <hr>
            <h2>Grant Lanham (<span class="at-symbol">@</span>declared7772): Lead developer; programming; code review</h2>
            <h2>Saptarshi (<span class="at-symbol">@</span>crease.1): Ex-lead-developer; asset management; programming; reverse engineering</h2>
            <h2>Finlay Metcalfe (<span class="at-symbol">@</span>ofthemaasses): Programming; code review</h2>
            <h2>SmilerRyan (<span class="at-symbol">@</span>smilerryan): Code review</h2>
            <h2>EVEN_STEEL (<span class="at-symbol">@</span>even_steel): Domain provider</h2>
            <h2>NeoTheb (<span class="at-symbol">@</span>neotheb): Ex-developer; programming</h2>
            <h2>dmn (<span class="at-symbol">@</span>dmn01): Logo designer</h2>
            <br><br><br><br><br><br><br>
            Names in parentheses are Discord usernames.
            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('content/footernavigation.php') ?>

</body>

</html>