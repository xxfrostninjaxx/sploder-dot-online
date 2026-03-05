<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once('../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$isolated = $userRepository->isIsolated($_SESSION['username'] ?? '');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <script type="text/javascript">window.rpcinfo = "Viewing All Messages";</script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="members" class="messages">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3 style="white-space:nowrap;">Sploder Revival Live Messages</h3>
            <p>This is a feed of all of the messages on Sploder Revival. Remember, for your safety, there are no private messages on Sploder Revival, and they can always be read here.</p>
            <?php if (!$isolated) { ?>
                <a id="messages_top"></a>
                <div id="messages"></div>
            <?php } else { ?>
                <div class="alert">You have disabled comments and friending on your profile. You cannot send, receive or view messages. You can go to your profile settings to enable it.</div>
            <?php } ?>
            <div id="venue"></div>
            <script type="text/javascript">

                window.onload = function () {
                    var n;
                    n = document.createElement('link');
                    n.rel = 'stylesheet';
                    n.type = 'text/css';
                    n.href = '/css/venue5.css';
                    document.getElementsByTagName('head')[0].appendChild(n);
                    n = document.createElement('script');
                    n.type = 'text/javascript';
                    n.src =  '/comments/include.js.php';
                    document.getElementsByTagName('head')[0].appendChild(n);
                    //if (onload2) onload2();
                }
                
            </script>
            <?php
                if (isset($_GET['creator'])) {
                    $owner = "creator-".$_GET['creator'];
                } else if (isset($_GET['owned'])) {
                    $owner = "owned-".$_GET['owned'];
                } else {
                    $owner = '0';
                }
            ?>
            <script type="text/javascript">
              us_config = {
                container: 'messages',
                venue: 'allmsgs',
                venue_container: 'venue',
                owner: '<?= $owner ?>',
                username: '<?= $_SESSION['username'] ?? null ?>',
                ip_address: '',
                timestamp: '',
                auth: '0',
                use_avatar: true,
                venue_anchor_link: true,
                show_messages: true,
                last_login: '0'
              }

             
            </script>
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