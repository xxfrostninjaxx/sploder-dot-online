<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once(__DIR__ . "/../repositories/repositorymanager.php");
require_once(__DIR__ . "/content/searchresult.php");
$searchUserName = $_GET['u'] ?? '';
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <script type="text/javascript">window.rpcinfo = "Searching for a Member";</script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="members" class="">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Member Search Results</h3>

            <p>Search for member by entering a search query below. If you wish to search for games, visit the <a
                    href="../games/search.php">games
                    search page</a>.
                </a>

            <form action="/members/search.php" method="GET">
                <label for="username" style="font-size: 16px;">Search for members: &nbsp;</label>
                <input type="text" name="u" value=<?= json_encode($searchUserName) ?? '""' ?> size="16" maxlength="16" class="biginput" />
                <input type="submit" value="Search" class="postbutton" />
            </form>
            <?php
            if ($searchUserName != null || $searchUserName != '') {
            ?>
            <h4>Members matching search <span class="tagcolor1"><?= htmlspecialchars($searchUserName) ?? null ?></span>:</h4>
            </p>
            
            <?php
                $searchResults = RepositoryManager::get()->getUserRepository()->search($searchUserName);
                generateSearchResults($searchResults);

            }
            ?>
        </div>
        <div class="spacer">&nbsp;

        </div>

        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>