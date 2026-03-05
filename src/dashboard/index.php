<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
include('../content/logincheck.php');
$username = $_SESSION['username'];
require_once('../database/connect.php');
require_once('../repositories/repositorymanager.php');

$friendsRepository = RepositoryManager::get()->getFriendsRepository();
$newFriends = $friendsRepository->getFriendRequestCount($_SESSION['userid'], false);
$db = getDatabase();
$userRepository = RepositoryManager::get()->getUserRepository();
$awardsRepository = RepositoryManager::get()->getAwardsRepository();
$newAwards = $awardsRepository->getAwardRequestCount($_SESSION['username'], false);
$level = $userRepository->getLevelByUserId($_SESSION['userid']);
$isolated = $userRepository->isIsolated($_SESSION['username']);
$friends = $friendsRepository->getTotalFriends($username);
$perms = $userRepository->getUserPerms($username);
$isModerator = $perms !== null && $perms !== '' && str_contains($perms, 'M');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
  <?php include('../content/head.php'); ?>
  <link href="css/css.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" type="text/css" href="../css/sploder_v2p3.css">
  <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
  <link rel="stylesheet" type="text/css" href="../slider/nivo-slider.css" />
  <link rel="stylesheet" type="text/css" href="../css/inline_help.css">
  <link rel="stylesheet" type="text/css" href="../css/dashboard.css">
  <link rel="stylesheet" type="text/css" href="./css/notifications.css">
  <style media="screen" type="text/css">
    #swfhttpobj {
      visibility: hidden
    }
  </style>
  <script type="text/javascript">window.rpcinfo = "On the Dashboard";</script>
  <?php include('../content/onlinechecker.php'); ?>

</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="" onload="doLoad();">
  <?php include('../content/headernavigation.php'); ?>
  <div id="page">
    <?php
    require_once('../services/DashboardSubnavService.php');
    $subnavService = new DashboardSubnavService();
    echo $subnavService->renderNavigationLinks($_SERVER['REQUEST_URI']);
    ?>
    <div id="content">
      <div class="prompt">Welcome, <?php echo $username ?>! What would you like to do?
      </div>
      <?php
      if(getenv('SPLODERHEADS_ENABLED') == 'true') {
          echo '
          <div class="promo" style="background-color:#a417a0"><b>SNEAK PEEK:</b> Check out our new <a href="/games/multiplayer.php">Sploder Multiplayer Game</a>!
          </div>
          ';
      }
      ?>
      <div id="new_status" class="dashboard">
        <div id="idbadge">
          <div>
            <a href="../accounts/avatar.php">
              <img src="../php/avatarproxy.php?u=<?= $username ?>&nocache=<?= time() ?>" alt="Edit Your Avatar" title="Edit Your Avatar" border="0"
                width="96" height="96">
            </a>
            <p class="badgename">
              <a href="../members/index.php?u=<?= $username ?>"
                title="View profile"><?php echo $username ?></a>
            </p>
            <?php if (!$isolated) { ?>
            <p>
              <a href="../friends/index.php"
                title="Manage friends"><?= $friends ?> friend<?= $friends == 1 ? '' : 's' ?></a>
            </p>
            <?php } ?>
            <p class="note"><abbr>Level
                <?php echo $level ?></abbr>
              <a style="background: url(../chrome/help_icon.gif) no-repeat top left" class="tooltip">&nbsp;<span><strong>How do I level up?</strong>
                  <br>You
                  level up by participating in Sploder Revival. Play games and vote on them,
                  create your own games, and make friends. As you do this, your level
                  will increase. Leveling up will unlock certain items in the creators,
                  and allow you to do more on Sploder Revival.
                </span>
              </a>
            </p>
          </div>
        </div>
        <ul class="actions">
          <li
          <?php
          if ($newFriends > 0) {
              echo 'class="wow"';
          } else {
            echo 'class="meh"';
            $newFriends = 'No';
          }
          ?>
          >
            <a <?= $newFriends == 'No' ? 'style="color:#666"' : '' ?> href="../friends/index.php"><?= $newFriends != 'No' ? '<strong>' : '' ?><?= $newFriends ?><?= $newFriends != 'No' ? '</strong>' : '' ?> new friend request<?= $newFriends == 1 ? '' : 's' ?>!</a>
          </li>
          <?php
          // Show new awards count only if there are any
          if ($newAwards > 0) {
              echo '<li class="wow">
              <a href="/awards/index.php"><strong>' . $newAwards . '</strong> new award' . ($newAwards == 1 ? '' : 's') . '!</a>
              </li>';
          }
          ?>
          <li>
            <a href="../make/index.php">Make
              your own game</a>
          </li>
          <li>
            <a href="../accounts/avatar.php">Change your
              avatar</a>
          </li>
          <li>
            <a href="../friends/index.php">Find friends</a>
          </li>
          <li>
            <a href="../make/graphics.php">Draw
              some graphics</a>
          </li>
          <?php
          if ($isModerator) {
              echo '<li><a href="../games/moderation/">Moderate site content</a></li>';
          }
          ?>
        </ul>
      </div>
      <div class="spacer">&nbsp;</div>
      <?php include('../content/checkban.php') ?>
      <?php if (checkBan($username)) { ?>
        <?php
        $banInfo = $userRepository->getBanInfo($username);
        ?>
        <div class="promo"><b>NOTICE: </b>Your account access has been limited. A moderator has disallowed you from publishing games, making comments or giving awards. You will be unbanned on <?= date('Y/m/d', $banInfo['autounbandate']) ?>.<br>Reason: <?= htmlspecialchars($banInfo['reason']) ?></div>
      <?php } ?>
      <?php /* include('../content/friendgamelist.php'); */ ?>
      <!-- <br> -->
      <!-- <div class="spacer">&nbsp;</div> -->
      <?php /* include('../content/friendactivity.php') */ ?>
      <!-- <br>
      <br> -->
      <?php if (!$isolated) { ?>
      <div class="notifications">
          <h4>Messages on your Pages</h4>
            </div>
            <div id="venue">
            </div>
            <!-- SWFHTTPRequest -->

            

            <!-- End SWFHTTPRequest -->

            <script type="text/javascript">
              us_config = {
                container: 'messages',
                venue: 'dashboard',
                venue_container: 'venue',
                owner: '<?= $username ?>',
                username: '<?= $username ?>',
                ip_address: '',
                timestamp: '',
                auth: '<?= $_SESSION['PHPSESSID'] ?>',
                use_avatar: true,
                venue_anchor_link: true,
                show_messages: true,
                last_login: '0'
              }

             
            </script>
            <a name="messages_top">
            </a>
            <div id="messages" style="text-align: left" ;="">
            </div>
            <br>
            <div class="pagination">
              <span class="button firstbutton">
                <a href="/messages/">Messages</a>
              </span>
            </div>
            <br>
        <h4>
        </h4>
      
      <br>
      <br style="clear: both;">
      <img src="./pixie.gif" width="1" height="1">
      <div class="spacer">&nbsp;
      </div>
      <?php } ?>
    </div>
    
    <div id="sidebar">

      <?php include('../content/powercharts.php') ?>

      <?php include('../content/onlinelist.php') ?>


      <br>
      <br>
      <br>
      <div class="spacer">&nbsp;</div>
    </div>
    <div class="spacer">&nbsp;</div>
    <?php include('../content/footernavigation.php') ?>
</body>

</html>
