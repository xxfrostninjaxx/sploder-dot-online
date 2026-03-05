<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require_once('content/index.php');
require_once('../repositories/repositorymanager.php');
require_once('../services/GameListRenderService.php');
require_once('../services/FriendsListRenderService.php');

$gameListRenderService = new GameListRenderService(RepositoryManager::get()->getGameRepository());
$friendsRepository = RepositoryManager::get()->getFriendsRepository();
$friends = $friendsRepository->getTotalFriends($_GET['u'] ?? '');
$userRepository = RepositoryManager::get()->getUserRepository();
$stats = $userRepository->getUserStats($_GET['u'] ?? '');
$friendsRepository = RepositoryManager::get()->getFriendsRepository();
$friendsListRenderService = new FriendsListRenderService($friendsRepository);
$ownerUsername = $_GET['u'] ?? '';
if (isset($_SESSION['loggedin'])) {
    $viewerPermissions = $userRepository->getUserPerms($_SESSION['username'] ?? '');
    $ownerUsername = str_contains($viewerPermissions, 'M') ? $_SESSION['username'] ?? '' : ($_GET['u'] ?? '');
}
$difficulty = $stats['avg_difficulty'] ?? 50;
$feedback = $stats['avg_score'] ?? 50;
$awesomeness = $stats['awesomeness'] ?? 50;
$nocache = time();
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/member_profile3.css" />
    <script type="text/javascript" src="js/friends.js"></script>
    <script type="text/javascript">window.rpcinfo = "Viewing Member";</script>
    <?php include('../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="members" class="">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php include('../content/subnav.php'); ?>
        <div id="content">
            <h3></h3>
            <div class="mprof">
                <div class="mprofgroup mprofvcard">
                    <a href="/members/index.php?u=<?= $username ?>"><img class="mprofavatar96"
                            src="../php/avatarproxy.php?u=<?= $username ?>&nocache=<?= $nocache ?>" width="96" height="96"
                            alt="<?= $username ?>" /></a>
                    <div class="mprofvitals">
                        <h2><a href="/members/index.php?u=<?= $username ?>"><?= $username ?></a></h2>
                        <div class="mprofstatus">
                            <img src="../php/userstatus.php?u=<?= $username ?>" width="80" height="25"
                                alt="online status" />
                            <?php
                            $result['perms'] = $result['perms'] ?? '';
                            $roles = [
                                'E' => 'editor',
                                'R' => 'reviewer',
                                'M' => 'moderator'
                            ];

                            foreach ($roles as $key => $role) {
                                // If no role, set to "empty"
                                $role = strpos($result['perms'], $key) !== false ? $role : "empty";
                                $icon = "role_$role";
                                echo "<img src=\"/chrome/{$icon}.gif\" width=\"24\" height=\"28\" alt=\"{$role}\" title=\"$role\" />";
                            }
                            ?>
                        </div>
                        <dl>
                            <dt><strong>Level</strong></dt>
                            <dd><strong><?= $result['level'] ?></strong></dd>
                            <dt>Joined:</dt>
                            <dd><?= time_elapsed_string("@" . $result['joindate']) ?></dd>
                            <dt>Last visit:</dt>
                            <dd>
                                <?php
                                $lastLoginTime = $result['lastlogin'];
                                // 120 second buffer
                                echo (time() - $lastLoginTime < 120) ? 'just now' : time_elapsed_string("@" . $lastLoginTime);
                                ?>
                            </dd>
                        </dl>
                        <div><div id="venue" class="mprofvenue"></div></div>
                        <?php
                        $isolated = $userRepository->isIsolated($username) || $userRepository->isIsolated($_SESSION['username'] ?? '');
                        if ($username !== ($_SESSION['username'] ?? '') && isset($_SESSION['loggedin']) && !$isolated) {
                            // Check if the user is already friends
                            $isFriend = $friendsRepository->alreadyFriends($_SESSION['username'], $username);
                            if ($isFriend) {
                                echo '<div style="float:right;"><a style="cursor:pointer;" onclick="handleRemoveFriend(event, \'' . $username . '\')">REMOVE FRIEND</a></div>';
                            } else {
                                echo '<div style="float:right;"><a style="cursor:pointer;" onclick="handleAddFriend(event, \'' . $username . '\')">ADD FRIEND</a></div>';
                            }
                        ?>
                        <?php
                        }
                        ?>
                    </div>
                </div>
                <div class="shown">
                    <div class="mprofgroup">
                        <div class="mprofchart mprofmain" title="Awesomeness (<?= $awesomeness ?>%) - computed using a secret recipe">
                            <img src="/images/charts/awesomeness/chart_<?php echo $awesomeness ?>.png" width="230" height="116" />
                            <p>Awesomeness</p>
                        </div>
                        <div class="mprofcount" title="total games/featured games">
                            <div class="stat"><?php 
                            if ($featuredgames != 0) {
                                echo $totalgames . '/' . $featuredgames;
                            } else {
                                echo $totalgames;
                            } ?> <span>Game<?= $totalgames == 1 ? '' : 's' ?></span></div>
                        </div>
                        <div class="mprofcount mprofend">
                            <div class="stat"><?php echo $friends ?> <span>Friend<?= $friends == 1 ? '' : 's' ?></span></div>
                        </div>

                        <div class="mprofchart" title="Average difficulty, all games combined">
                            <img src="/images/charts/difficulty/chart_<?= $difficulty ?>.png" width="160" height="70" />
                            <p>Difficulty</p>
                        </div>
                        <div class="mprofchart mprofend" title="Average votes, from all players">
                            <img src="/images/charts/feedback/chart_<?= $feedback ?>.png" width="160" height="70" />
                            <p>Feedback</p>
                            <br>
                        </div>
                        <div class="mprofstat mprofmain" title="Total views for all games combined">
                            <div class="stat"><?= $plays ?></div>
                            <p>Total Plays</p>
                        </div>
                        <div class="mprofstat" title="Average play time per game play">
                            <div class="stat"><?= $playtime ?></div>
                            <p>Play Time</p>
                        </div>
                        <div class="mprofstat mprofend" title="Total Votes Received from other players">
                            <div class="stat"><?= $votes ?></div>
                            <p>Total Votes</p>
                        </div>
                        <div class="spacer">&nbsp;</div>
                    </div>
                    <?php include('../content/userinfo.php');
                    display_user_info($username);
                    ?>



                </div>
            </div>
            <h4 class="mprofgames">Games by <?= $username ?></h4>
                <?php
                    $gameListRenderService->renderPartialViewForUser($username, $_GET['o'] ?? 0, 12);
                ?>

                <!-- SWFHTTPRequest - for browsers that don't support CORS -->

                <!--
            <div id="communicator" style="position: fixed; top: 1px; left: 1px;">
                <div id="swfhttpobj"></div>
                <script type="text/javascript">
                       swfobject.embedSWF('https://sploder.us/swfhttprequest.swf', 'swfhttpobj', '1', '1', '9', '/swfobject/expressInstall.swf', null, { allowScriptAccess: 'always', bgcolor: '#000000' });
                </script>
            </div>
            -->

                <!-- End SWFHTTPRequest -->


                           <script type="text/javascript">
            us_config = {
                container: 'messages',
                venue: 'messages-<?= $username ?>',
                venue_container: 'venue',
                venue_type: 'member',
                owner: '<?= $ownerUsername ?>',
                username: '<?php if (isset($_SESSION['username'])) {
                        echo $_SESSION['username'];
                           }?>',
                ip_address: '',
                timestamp: '<?= time() ?>',
                auth: '',
                use_avatar: true,
                venue_anchor_link: true,
                show_messages: true,
            }

            window.onload = function() {
                var n;
                n = document.createElement('link');
                n.rel = 'stylesheet';
                n.type = 'text/css';
                n.href = '/css/venue5.css';
                document.getElementsByTagName('head')[0].appendChild(n);
                n = document.createElement('script');
                n.type = 'text/javascript';
                n.src = '/comments/venue7.js';
                document.getElementsByTagName('head')[0].appendChild(n);
                if (window.addthis) addthis.button('#btn1', addthis_ui_config, addthis_share_config);
            }
            </script>

                <?php if (!$isolated) { ?>
                <a id="messages_top"></a>
                <div id="messages"></div>
                <?php } ?>
                <div class="spacer">&nbsp;</div>
                <?php
                echo $friendsListRenderService->renderPartialViewForMemberFriends($username);
                ?>
                <div class="spacer">&nbsp;</div>
            </div>
            <div class="spacer friends_spacer">&nbsp;</div>
            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">




            <div class="motd_widget motd_winner">
                <p>Member of the Day: Coming soon!</p>
                <div class="spacer">&nbsp;</div>
            </div>



            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>
