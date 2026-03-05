<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once('../repositories/repositorymanager.php');

$userRepository = RepositoryManager::get()->getUserRepository();

$perPage = 100;
$offset = $_GET['o'] ?? 0;
$users = $userRepository->getMembers($offset);
$totalMembers = $userRepository->getTotalNumberOfMembers()
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css"  href="/css/allmembers.css" />
    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime()
    </script>
    <script type="text/javascript">window.rpcinfo = "Viewing the Members List";</script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="members" class="allmembers">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
                <h3>All Sploder Revival Members</h3>
                <p>This is a list of all Sploder Revival members, in order of joining. Go to the <a href="?o=<?= floor($totalMembers/100) ?>">end of the list</a> 
	            to see the newest members.</p>
                <form action="/members/search.php" method="get">
				<label for="search_username" style="font-size: 16px;">Search for members: &nbsp;</label>
				<input type="text" id="search_username" name="u" value="" size="16" maxlength="16" class="biginput" />
				<input type="submit" value="Search" class="postbutton" />
			</form><br /><br /><br />
            <table class="users" width="100%">
                <tr><th>User</th><th>Rank</th><th>Games</th><th>Friends</th><th>Views</th><th>Votes</th></tr>
                <?php
                    $rowClass = 'even';
                    foreach ($users as $user) {
                        echo "<tr class=\"$rowClass iconsmall\">\n";
                        echo "<td class=\"userlink\"><a href=\"/members/index.php?u={$user['username']}\" title=\"{$user['username']}\"><img src=\"/php/avatarproxy.php?size=24&u={$user['username']}\" width=\"24\" height=\"24\" /> {$user['username']}</a></td>";
                        echo "<td class=\"stat rank\">{$user['level']}</td>";
                        echo "<td class=\"stat\">{$user['game_count']}</td>";
                        echo "<td class=\"stat\">{$user['friend_count']}</td>";
                        echo "<td class=\"stat\">{$user['total_views']}</td>";
                        echo "<td class=\"stat\">{$user['total_ratings_received']}</td>";
                        echo "</tr>";
                        $rowClass = ($rowClass === 'even') ? 'odd' : 'even';
                    }
                    ?>
             </tr>
	</table>
    <?php
    require_once(__DIR__ . '/../content/pages.php');
    addPagination($totalMembers, $perPage, $offset);
    ?>
        </div>
        <div class="spacer">&nbsp;</div>
    
    <div id="sidebar">
        <br /><br /><br />
        <div class="spacer">&nbsp;</div>
    </div>
    <div class="spacer">&nbsp;</div>
    <?php include('../content/footernavigation.php') ?>
</body>

</html>