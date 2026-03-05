<?php
require_once 'content/initialize.php';
require(__DIR__.'/content/disablemobile.php'); ?>
<?php
session_start();
require_once('repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$ownerUsername = 'staff';
if (isset($_SESSION['loggedin'])) {
    $perms = $userRepository->getUserPerms($_SESSION['username'] ?? '');
    $ownerUsername = str_contains($perms, 'M') ? $_SESSION['username'] ?? '' : 'staff';
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('content/head.php'); ?>
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/members.css" />
    <style media="screen" type="text/css">
        #swfhttpobj {
            visibility: hidden
        }
    </style>
    <?php include('content/ruffle.php'); ?>


    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

    <script type="text/javascript">
        var _sf_startpt = (new Date()).getTime()
    </script>


    <link href="/css/members.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript">window.rpcinfo = "Viewing Staff Members";</script>
    <?php include('content/onlinechecker.php'); ?>
</head>
<?php include('content/addressbar.php'); ?>

<body id="members" class="staff" onload="doLoad();">
    <?php include('content/headernavigation.php'); ?>
    <div id="page">
        <?php include('content/subnav.php') ?>
        <div id="content">
            <h3>Sploder Revival Staff</h3>
            <div id="venue" style="display: none;"></div>



            <div class="gameobject" style="width: 570px; height: 428px;">

                <div id="spotlight">

                    <br /><br /><br /><br /><br /><br />
                    <center>Loading staff members...</center><br /><br /><br /><br /><br /><br />

                </div>

            </div>



            <script type="text/javascript">
                swfobject.embedSWF('../swf/staffmembers.swf', 'spotlight', '570', '428', '8',
                    '/swfobject/expressInstall.swf', null, {
                        bgcolor: ""
                    });
            </script>



            <p>Welcome to the Sploder Revival Staff page. All staff are Sploder Revival members who have devoted their
                time to make Sploder Revival a better place.

                Please treat them with respect, and they will do the same for you!</p>


            <?php require_once('content/getstaffmembers.php') ?>
            <p>Our <strong>editors</strong> work tirelessly to find and feature good games. Our
                <strong>moderators</strong> follow the comments and games to make sure they

                are appropriate for a general audience, and make sure everyone is treated nicely on Sploder. Our
                <strong>reviewers</strong> write reviews to help us all learn what

                makes a good game. Feel free to comment here to communicate directly with our staff.
            </p>


            <div class="memberboard">
                <h4>Editors</h4>
                <ul class="contentpromo memberlist">
                    <?php
                    renderStaffList($staff['editors']);
                    ?>
                </ul>
                <div class="spacer">&nbsp;</div>
            </div>
            <div class="memberboard rightcol">
                <h4>Moderators</h4>
                <ul class="contentpromo memberlist">
                    <?php
                    renderStaffList($staff['moderators']);
                    ?>
                </ul>
                <div class="spacer">&nbsp;</div>
            </div>
            <div class="spacer">&nbsp;</div>
            <hr />
            <!-- SWFHTTPRequest - for browsers that don't support CORS -->

            <!--
            <div id="communicator" style="position: fixed; top: 1px; left: 1px;">
                <div id="swfhttpobj"></div>
                <script type="text/javascript">
                       swfobject.embedSWF('https://sploder.us/swfhttprequest.swf', 'swfhttpobj', '1', '1', '9', '/swfobject/expressInstall.swf', null, { allowScriptAccess: 'always', bgcolor: '#000000' });
                </script>
            </div>
            -->

            <script type="text/javascript">
            us_config = {
                container: 'messages',
                venue: 'staff-page',
                venue_container: 'venue',
                venue_type: 'staff',
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


            <a id="messages_top"></a>
            <div id="messages"></div>
            <div class="spacer">&nbsp;</div>

            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">


            <div class="memberboard">
                <h4>Reviewers</h4>
                <ul class="contentpromo memberlist">
                    <?php
                    renderStaffList($staff['reviewers']);
                    ?>
                </ul>
                <div class="spacer">&nbsp;</div>
            </div>


            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div><?php include('content/footernavigation.php') ?>
</body>

</html>