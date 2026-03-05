<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require_once('content/publish.php');
require_once('../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$isolated = $userRepository->isIsolated($_SESSION['username']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php
    if ($game['g_swf'] == 1) {
        include('../content/ruffle.php');
    }
    ?>
    <title>Sploder</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="../css/sploder_v2p12.css" type="text/css" />
    <link rel="stylesheet" type="text/css" href="/css/scrollbar.css" />
    <link rel="stylesheet" href="../css/publishpage2.css" type="text/css" />
    <?php //require('../content/swfobject.php'); 
    ?>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>
    <script type="text/javascript" src="content/publish.js"></script>
    <script>
    id = <?= $id ?>;
    window.parent.postMessage({ type: "iframe-audio", state: "playing" }, "*");
    </script>
</head>

<body bgcolor="#FFFFFF">
    <div id="show" style="width: 590px;"><a name="kickdown" style="height:1px; overflow:hidden;"></a>
        <div class="showcontent">
            <h4><?= htmlspecialchars($game['title']) ?></h4>
            <?php
            if ($game['ispublished'] == 0) {
                echo '<div class="alert">This is an unpublished game. Either publish it, or test it out in the creator.</div>';
            } else {
                if ($game['isprivate'] == 1) {
                    echo '<div class="alert">This game is private, but you have the key!</div>';
                }
            ?>
            <div class="gameobject" style="width: 508px; height: 381px;">
                <div id="flashcontent">
                    <br /><br /><br /><br /><br /><br />
                    <p style="text-align: center;">Loading game...</p>

                    <br /><br /><br /><br /><br /><br />
                </div>
            </div>
            <script type="text/javascript">
            var g_swf = "game<?= $game['g_swf'] ?>.swf";
            var g_version = "10";

            try {
                if (g_swf == "game2.swf") {
                    var fmv = deconcept.SWFObjectUtil.getPlayerVersion().major;
                    if (fmv == "9") {
                        g_swf = "game2v9.swf";
                        g_version = "9";
                    }
                }
            } catch (err) {}

            var flashvars = {
                s: "<?= $game['user_id'] . '_' . $game['g_id'] ?>",
                <?php if (isset($_SESSION['PHPSESSID'])) {
                            echo "sid: \"{$_SESSION['PHPSESSID']}\",\n";
                        } else {
                            echo 'nu: "",' . "\n";
                        } ?>
                // EMBED_BETA_VERSION
                // EMBED_FORCE_SECURE
                // EMBED_ADTEST
                // EMBED_CHALLENGE
                beta_version: "<?= $game['g_swf_version'] ?>",
                onsplodercom: "true",
                modified: 9999999,
                <?php if (isset($_SESSION['PHPSESSID'])) {
                            echo "PHPSESSID: \"{$_SESSION['PHPSESSID']}\"";
                        } ?>
            }

            var params = {
                menu: "false",
                quality: "high",
                scale: "noscale",
                salign: "tl",
                bgcolor: "#333333",
                wmode: "direct",
                allowScriptAccess: "always",
            };

            swfobject.embedSWF("/swf/" + g_swf, "flashcontent", "508", "381", g_version,
                "/swfobject/expressInstall.swf", flashvars, params);
            </script>
            <div style="display:none;" id="message" class="prompt"></div>
            <br id="promptBr">
            <p class="description" id="descriptionBox" style="overflow: hidden; border: 1px solid #999; padding: 10px; margin: 0;<?php if ($game['description'] == null) {
                                                                                                    echo 'display:none;';
                                                                                                } ?>">
                <?= nl2br(htmlspecialchars($game['description'] ?? '')) ?></p>
            <br><br>
            <div class="buttons" style="padding: 0;">
                <span class="button firstbutton"><a style="cursor:pointer;" onclick="showDescription()">Describe
                        &raquo;</a></span>&nbsp;
            </div>

            <br>

            <div style="display:none;" id="description">
                <hr>
                <p>Please enter a description for your game.</p>
                <textarea id="descriptionTextarea" type="text" name="description" size="50" maxlength="2000"
                    style="width: 300px; height: 200px;"><?= br2nl($game['description']) ?></textarea><br><br>
                <input onclick="sendDescription()" type="submit" value="Save Description"
                    class="loginbutton postbutton">
                <br><br>
            </div>
            <hr>
            <div id="kickdown" class="tagbox">
                <p class="tags" style="text-align:justify !important;">
                    <?php if (!isset($tags[0][0])) { ?>
                    <strong><span
                            style="all: unset; font-weight: bold; font-family: Verdana, Arial, Helvetica, sans-serif; color:#0055FC;"><big>ONE
                                MORE STEP!</big></span></strong>
                    Please add some tags to describe your game
                    (like <a class="tagcolor0">space</a>,
                    <a class="tagcolor1">adventure</a>,
                    <a class="tagcolor2">rpg</a>,
                    <a class="tagcolor3">monster</a>,
                    <a class="tagcolor0">alien</a>).
                    Use any words you like:
                    <?php } else {
                            require('../content/taglister.php');
                        ?>
                    Tags: <?= displayTags($tags, false) ?>
                    <?php } ?>
                </p>
                <input type="hidden" name="id" value="<?= $id ?>">
                <textarea type="text" id="tagsText" name="tags" size="50" style="width: 300px; height: 100px;"><?php
                                                                if (isset($tags[0][0])) {
                                                                    $tagString = '';
                                                                    foreach ($tags as $tag) {
                                                                        $tagString .= $tag[0] . ' ';
                                                                    }
                                                                    $tagString = substr($tagString, 0, -1);
                                                                    echo $tagString;
                                                                }
                                                                ?></textarea><br><br>
                <input type="submit" onclick="sendTags()" value="Save Tags" class="loginbutton postbutton">

            </div>
            
            <script type="text/javascript">
            us_config = {
                container: 'messages',
                venue: 'game-<?= $_SESSION['userid'] . '_' . $id . '-' . $game['author'] ?>',
                venue_container: 'venue',
                venue_type: 'game',
                owner: '<?= $game['author'] ?>',
                username: '<?php if (isset($_SESSION['username'])) {
                                        echo $_SESSION['username'];
                                    } ?>',
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
                n.href = '../css/venue5.css';
                document.getElementsByTagName('head')[0].appendChild(n);
                n = document.createElement('script');
                n.type = 'text/javascript';
                n.src = '../comments/venue7.js';
                document.getElementsByTagName('head')[0].appendChild(n);
                if (window.addthis) addthis.button('#btn1', addthis_ui_config, addthis_share_config);
            }
            </script>

            <div style="text-align:left;">
            <?php
            if (!$isolated && $game['comments'] == 1) {
            ?><hr>
                <div id="messages"></div>
                <div id="venue" class="mprofvenue"></div>
            <?php } ?>
            </div>
            <?php } ?>
        </div>

        <div class="showbottom">
            <div class="showbottomright">&nbsp;</div>
        </div>
    </div><br /><br>
</body>

</html>