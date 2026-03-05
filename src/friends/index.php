<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
include('../content/logincheck.php');
require_once('../database/connect.php');
$db = getDatabase();
require_once('../repositories/repositorymanager.php');
require_once('../services/FriendsListRenderService.php');
$friendsRepository = RepositoryManager::get()->getFriendsRepository();
$friendsRepository->setAllFriendsAsViewed($_SESSION['userid']);
$userRepository = RepositoryManager::get()->getUserRepository();
$friendsService = new FriendsListRenderService($friendsRepository);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/friends2.css" />
    <style media="screen" type="text/css">
    #swfhttpobj {
        visibility: hidden
    }
    </style>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>


    <link href="/css/members.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" language="Javascript">
    </script>
    <?php include('../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Managing their Friends";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="friendsmanager" class="friend" onload="doLoad();">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php
        require_once('../services/DashboardSubnavService.php');
        $subnavService = new DashboardSubnavService();
        echo $subnavService->renderNavigationLinks($_SERVER['REQUEST_URI']);
        ?>
        <div id="content">
            <h3>Manage My Friends</h3>
            <?php if (isset($_GET['err'])) {
                $err = $_GET['err'];
                if ($err == "you") { ?>
            <div class="alert">You cannot friend yourself!</div>
                <?php } elseif ($err == "no") { ?>
            <div class="alert">That user does not exist!</div>
                <?php } elseif ($err == "sent") { ?>
            <div class="alert">You/That user have already sent a friend request to that user/you!</div>
                <?php } elseif ($err == "suc") { ?>
            <div class="prompt">Friend request sent successfully!</div>
                <?php } elseif ($err == "that") { ?>
            <div class="alert">That user is already your friend!</div>
                <?php } elseif ($err == "before") { ?>
            <div class="alert">That user revoked the request before you could accept it!</div>
                <?php } elseif ($err == "isolated") { ?>
            <div class="alert">That user has disabled comments and friending on their profile!</div>
                <?php }
            } ?>
            <?php
            $isolated = $userRepository->isIsolated($_SESSION['username']);
            if ($isolated) {
                echo '<div class="alert">You have disabled comments and friending on your profile. You cannot send or receive friend requests. You can go to your profile settings to enable it.</div>';
            } else {
            ?>
            <h4>New Friend Requests</h4>
            <?php
            $result = $db->query("SELECT sender_username
                FROM friend_requests
                WHERE receiver_id=:sender_id
                ORDER BY request_id DESC", [
                    ':sender_id' => $_SESSION['userid']
            ]);

            for ($i = 0; $i < count($result); $i++) {
                echo '<div class="friend_request_new friend_request"><img src="../php/avatarproxy.php?size=24&u=' . $result[$i]['sender_username'] . '"> ' . $result[$i]['sender_username'] . ' has requested to add you as a friend.<span style="width:200px"><a href="php/ignore.php?u=' . $result[$i]['sender_username'] . '">Ignore</a> | <a href="php/accept.php?u=' . $result[$i]['sender_username'] . '">Accept</a></span></div>';
            }
            if (count($result) == 0) {
                echo '<div style="text-align:center" class="friend_request">You have no pending friend requests!</div>';
            }
            ?>
            <h4>Sent Requests</h4>
            <?php
            $result = $db->query("SELECT receiver_username
                FROM friend_requests
                WHERE sender_id=:sender_id
                ORDER BY request_id DESC", [
                    ':sender_id' => $_SESSION['userid']
                ]);
            for ($i = 0; $i < count($result); $i++) {
                echo '<div class="friend_request"><img src="../php/avatarproxy.php?size=24&u=' . $result[$i]['receiver_username'] . '">You\'ve requested to become friends with ' . $result[$i]['receiver_username'] . '.<span><a href="php/revoke.php?u=' . $result[$i]['receiver_username'] . '">Revoke</a></span></div>';
            }
            if (count($result) == 0) {
                echo '<div style="text-align:center" class="friend_request">You have not sent any request!</div>';
            }
            ?><h4>Send a Request</h4>
            <div class="friend_chooser">

                <h4>Send friend request</h4>
                <form action="php/request.php" method="GET">
                    <label for="friendname">Enter your friend's username:</label>
                    <input type="text" id="friendname" name="username" required autocomplete="off" autocorrect="off"
                        autocapitalize="off" spellcheck="false" maxlength="16" />
                    <input style="width:auto;text-align:left;" type="submit" name="submit"
                        class="postbutton" value="Send" />
                </form>
            </div>
            <?php
                echo $friendsService->renderPartialViewForRecentFriends($_SESSION['username']);
            ?>
            <?php } ?>
            <div class="buttons" style="margin:0px; padding:0px;">
                <span style="float:right;" class="firstbutton button"><a href="all.php">Manage my friends</a></span>
            </div>

            <div class="spacer">&nbsp;</div>

            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">
            <script type="text/javascript">
            window.onload = function() {
                var n;
                n = document.createElement('link');
                n.rel = 'stylesheet';
                n.type = 'text/css';
                n.href = '../css/venue5.css';
                document.getElementsByTagName('head')[0].appendChild(n);
                n = document.createElement('script');
                n.type = 'text/javascript';
                document.getElementsByTagName('head')[0].appendChild(n);
                if (onload2) onload2();
            }
            </script>
            <?php include('../content/onlinelist.php') ?>

        </div>
        <div class="spacer">&nbsp;</div><?php include('../content/footernavigation.php') ?>
</body>

</html>
