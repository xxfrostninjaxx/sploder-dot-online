<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once('../repositories/repositorymanager.php');
require_once('../members/treemap/treemap.php');

$userRepository = RepositoryManager::get()->getUserRepository();
$topMembers = $userRepository->getTopMembers();
$showList = $userRepository->showOnlineList();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/treemap.css" />
    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Viewing Top Members";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="members" class="">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Today's Top Members</h3>
            <p>Welcome to the Sploder Revival members home page!  This page shows the top members for today.  The members who are getting the most gameplays today are listed here. <!-- No false advertising: This page is updated hourly. --><!-- TODO: To see the top members of all time, visit our <a href="/members/hall-of-fame/">Hall of Fame</a>.--> To meet and communicate with our staff, visit the <a href="/staff.php">Sploder Revival Staff</a> page.</p>
            <div class="button" style="float: right; width: 120px; padding-top: 13px;"><a href="/members/all.php">All members &raquo;</a></div> <form action="/members/search.php" method="get">
				<label for="search_username" style="font-size: 16px;">Member Search: &nbsp;</label>
				<input type="text" id="search_username" name="u" value="" size="16" maxlength="16" class="biginput" />
				<input type="submit" value="Search" class="postbutton" />
			</form><br /><br /><br />
            <?php
            $baseurl = "/";

            // squash time if necessary.  1 is unsquashed.
            $timesquash = 1;

            $tagArray = array_column($topMembers, 'total_views', 'author');
            $taggedArray = array_column($topMembers, 'last_view_time', 'author');

            if (count($tagArray) > 0) {
                // define timespan
                $fromwhen = date("Y-m-d H:i:s", time() - 60*60*24*7);
                $towhen = date("Y-m-d H:i:s", time());

                // sort the array according to size
                arsort($tagArray);
                    
                // call the function
                echo render_treemap($tagArray, 570, 900, 0, 1);
            }
            
            ?>
        </div>        
    <div id="sidebar">
        <?php
        if ($showList) {
            require('../content/onlinelist.php');
        }
        ?>
        <div class="spacer">&nbsp;</div>
    </div>
    <div class="spacer">&nbsp;</div>
    <?php include('../content/footernavigation.php') ?>
</body>

</html>