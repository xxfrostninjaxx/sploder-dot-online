<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}


require_once('../content/getgameid.php');
require('../content/playgame.php');
require_once('../content/taglister.php');
require_once('../repositories/repositorymanager.php');
require_once('../services/ChallengesService.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
$userRepository = RepositoryManager::get()->getUserRepository();
$challengesService = new ChallengesService();

$challenge = false;
$game_id = get_game_id($_GET['s']);
$game = get_game_info($game_id['id']);
if ($game_id['userid'] != $game['user_id']) {
    die("Invalid game ID");
}
if ($game['isdeleted'] == 1 || $game['ispublished'] == 0) {
    if (($_SESSION['username'] ?? '') != $game['author']) {
        header('Location: /');
        die();
    }
}
$status = "playing";
$creator_type = to_creator_type($game['g_swf']);
$isolated = $userRepository->isIsolated($game['author']) || $userRepository->isIsolated($_SESSION['username'] ?? '');


if(isset($_GET['challenge'])){
    $challengesRepository = RepositoryManager::get()->getChallengesRepository();
    $challengeId = $_GET['challenge'];

    // Verify if challengeId is correct
    if($challengesRepository->verifyChallengeId($game_id['id'], $challengeId, $_SESSION['challenge'] ?? -1)) {
        $challenge = true;
        $challengeInfo = $challengesRepository->getChallengeInfo($game_id['id']);
        $mode = "CHALLENGE ACCEPTED! ".$challengesService->formatChallengeMode($challengeInfo['mode'], $challengeInfo['challenge']);
    } else {
        $challenge = false;
    }
}
if (isset($_SESSION['challenge']) && !$challenge) {
    unset($_SESSION['challenge']);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php
    if ($game['g_swf'] == 1) {
        include('../content/ruffle.php');
        // Ruffle bug
        $domain = getenv('DOMAIN_NAME');
        if (strpos($domain, 'https://') === 0) {
            echo '<meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">';
        }
    }
    ?>
    <?php include('../content/head.php') ?>
    <link rel="alternate nofollow" type="application/rss+xml" title="RSS" href="/gamefeed.php" />
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/venue5.css" />
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/swfobject/2.2/swfobject.js"></script>

    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include('../content/onlinechecker.php'); ?>
    <?php
    if ($game['isprivate'] == 0 && $game['ispublished'] == 1 && $game['isdeleted'] == 0) {
        echo '<script type="text/javascript">window.rpcinfo = ' . json_encode("Playing: " . $game['title']) . ';</script>';
    } else {
        echo '<script type="text/javascript">window.rpcinfo = "Playing a Game";</script>';
    }
    ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="gamepage">
    <?php include('../content/headernavigation.php'); ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>

        <div id="content">
            <h3><?= htmlspecialchars($game['title']) ?></h3>
            <h4 class="subtitle">By <a href="/members/index.php?u=<?= $game['author'] ?>"><?= $game['author'] ?></a> ::
                <?= date('l F jS, Y', strtotime($game['last_published_date'])) ?></h4>

            <div class="vote" id="contestwidget">
                <div style="margin-top:-15px; width: 150px; height:45px; overflow: hidden;" id="contestflash">&nbsp;
                </div>
            </div>
            <div id="venue" style="margin: 6px 0 0 20px; float: right;"></div>
            <?php
            $isEditor = false;
            $isReviewer = false;
            $isModerator = false;
            $isEditorOrReviewer = false;
            if (isset($_SESSION['loggedin']))
            {
                $perms = $userRepository->getUserPerms($_SESSION['username']);
                if ($perms != null || $perms !== '') {
                    $isEditor = str_contains($perms, 'E');
                    $isReviewer = str_contains($perms, 'R');
                    $isModerator = str_contains($perms, 'M');
                    $isEditorOrReviewer = $isEditor || $isReviewer;
                    if ($isEditorOrReviewer && $game['author'] != $_SESSION['username']) {
                        echo '<style>.challenge_prompt { margin-top: -10px !important; }</style>';
                    }
                }
?>
<div style="display: flex; justify-content: flex-end; align-items: center; gap: 10px; margin-top: 10px;">
    <?php if (
        $isEditor && $game['isprivate'] == 0 && $game['ispublished'] == 1 && $game['isdeleted'] == 0 && $game['author'] != $_SESSION['username']
    ) {
        $isFeatured = $gameRepository->getFeaturedStatus($game['g_id']);
        ?>
        <script type="text/javascript" src="actions.js"></script>
        <a onclick="featureGame(<?= $game['g_id'] ?>, <?= $isFeatured ? 'false' : 'true' ?>)" id="featureGameLink" style="cursor:pointer;">
            <?= $isFeatured ? 'Unfeature' : 'Feature' ?> Game
        </a>
    <?php }
    if (
        $isReviewer && $game['isprivate'] == 0 && $game['ispublished'] == 1 && $game['isdeleted'] == 0 && $game['author'] != $_SESSION['username']
    ) {
        $isReviewed = $gameRepository->hasUserReviewedGame($_SESSION['userid'], $game['g_id']);
        if ($isEditor) { ?>
            <span>|</span>
        <?php } ?>
        <a href="/games/make-review.php?s=<?= $game['user_id'] . '_' . $game['g_id'] ?>">
            <?= $isReviewed ? 'Edit' : 'Write' ?> Review
        </a>
    <?php } ?>
</div>
		<?php } ?>
            <script>
            window.g_id = <?= $game['g_id'] ?>;
            swfobject.embedSWF("/swf/contest.swf", "contestflash", "150", "30", "8", "/swfobject/expressInstall.swf", { g: window.g_id}, { bgcolor: "#000000", menu: "false", quality: "high", scale: "noscale", salign: "tl", wmode: "opaque" });
            </script>
            <?php
            $showPrompt = false;
            ?>
            <?php if ($game['ispublished'] != 1) { $showPrompt = true; ?>
            <br><br>
            <div class="alert">This game is not published and cannot be played unless published!</div>
            <?php } else if ($game['isdeleted'] == 1) { $showPrompt = true; ?>
            <br><br>
            <div class="alert">This game is deleted and can only be played by you!</div>
            <?php } else if ($game['isprivate'] == 1) { $showPrompt = true; ?>
            <br><br>
            <div class="alert">This game is private but you have the key!</div>
            <?php } else if ($isEditorOrReviewer && $game['isprivate'] == 0 && $game['ispublished'] == 1 && $game['isdeleted'] == 0 && $game['author'] != $_SESSION['username']) { $showPrompt = true; } ?>

            <?php
            $challengePromptEditor = false;
            $challengePrompt = false;
            if(!$showPrompt) { echo '<br><br>'; }
            if((!$challenge) && (isset($_GET['challenge']))) {
                if ($isEditorOrReviewer && $game['isprivate'] == 0 && $game['ispublished'] == 1 && $game['isdeleted'] == 0 && $game['author'] != $_SESSION['username']) {
                    echo '<br><br>';
                    $challengePromptEditor = true;
                }
                if($challengesRepository->hasWonChallenge($game_id['id'], $_SESSION['userid'] ?? -1)) {
                    echo '<div class="challenge_prompt">Woo hoo! You won this challenge!</div>';
                } else {
                    echo '<div class="challenge_prompt">Yo ho ho! Log in to accept this challenge!</div>';
                }
                $challengePrompt = true;
            }
            if($challenge) {
                if ($isEditorOrReviewer && $game['isprivate'] == 0 && $game['ispublished'] == 1 && $game['isdeleted'] == 0 && $game['author'] != $_SESSION['username']) {
                    echo '<br><br>';
                    $challengePromptEditor = true;
                }
                echo '<div class="challenge_prompt">'.$mode.'</div>';
                $challengePrompt = true;
            }
            ?>
            <div class="gameobject">
                <div id="flashcontent">
                    <img class="game_preview"
                        src="../users/user<?= $game['user_id'] ?>/images/proj<?= $game['g_id'] ?>/image.png" />
                    <?php
                    if ($game['ispublished'] == 1) {
                    ?>
                    <p class="game_loading"
                        style="font-size: 14px; line-height: 16px; width: 500px; padding: 20px; margin-left: -130px; margin-top: 0px;">
                        Your browser does not support the technology to run this game<br><br>
                        You must download our launcher to run it<br><br>
                        Games made with the shooter creator will still work on your browser<br><br>
                        <a href="<?= getenv("LAUNCHER_REPOSITORY_URL") ?>/releases/latest" target="_blank">
                            <img border="0" alt="Download" src="/images/download.gif" />
                        </a>
                    </p>
                    <?php } ?>
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

                s: "<?= $_GET['s'] ?>",

                <?php if (isset($_SESSION['PHPSESSID'])) {
                        echo "sid: \"{$_SESSION['PHPSESSID']}\",\n";
                } else {
                    echo 'nu: "1",' . "\n";
                } ?>

                // EMBED_BETA_VERSION
                // EMBED_FORCE_SECURE
                // EMBED_ADTEST
                // EMBED_CHALLENGE

                beta_version: "<?= $creator_type->swf_version(); ?>",

                onsplodercom: "true",
                <?php
                if($challenge) {
                    echo 'challenge: "'.$_GET['challenge'].'",';
                    if(!$mode) {
                        echo 'chscore: "'.$challengeInfo['challenge'].'",';
                    } else {
                        echo 'chtime: "'.$challengeInfo['challenge'].'",';
                    }
                }
                ?>
                modified: <?= strtotime($game['last_published_date']) ?>,
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
            <?php
            if ($game['ispublished'] == 1) {
            ?>
            swfobject.embedSWF("/swf/" + g_swf, "flashcontent", "640", "480", g_version,
                "/swfobject/expressInstall.swf", flashvars, params);
            <?php } ?>
            </script>

            <div class="sharebar">
                <a href="/make/index.php">
                    <img style="float: left;" src="/chrome/social_bar_make.gif" width="210" height="36" alt="make a game" />
                </a>
                <?php
                    $currentUrl = getenv('DOMAIN_NAME') . $_SERVER['REQUEST_URI'];
                    // $fbUrl = "https://www.facebook.com/sharer.php?u=" . urlencode($currentUrl);
                    $tweetText = "Playing " . htmlspecialchars($game['title']) . " by " . htmlspecialchars($game['author']) . " on @sploder - ";
                    $twitterUrl = "https://twitter.com/intent/tweet?text=" . urlencode($tweetText) . "&url=" . urlencode($currentUrl);
                ?>
                <div class="share_buttons">
                    <!-- <a class="facebook"
                        href="<?php // $fbUrl ?>"
                        onclick="window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=380,width=550');return false;"
                        title="share this on facebook"></a> -->
                    <a class="twitter"
                        href="<?= $twitterUrl ?>"
                        onclick="window.open(this.href,'','menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height=380,width=550');return false;"
                        title="tweet this!"></a>
                </div>
            </div>
            <?php
            if (isset($game['description'])) {
                echo '<p class="description" style="overflow: hidden; border: 1px solid #999; padding: 10px; margin: 0; ">' . nl2br(htmlspecialchars($game['description'])) . '</p>';
            }

            // Get reviews
            $reviews = $gameRepository->getReviewsForGame($game['g_id']);
            if ($reviews) {
                echo '<div class="morelists"><h4>Game Reviews</h4><ul style="color:#ccc;">';
                foreach ($reviews as $review) {
                    echo '<li><a href="view-review.php?s=' . $game['user_id'] . '_' . $game['g_id'] . '&userid=' . $review['userid'] . '">' . htmlspecialchars(($review['title'])) . '</a> a review by <a style="font-weight: normal; color: #fff;" href="../members/?u=' . htmlspecialchars($review['username']) . '">' . htmlspecialchars($review['username']) . '</a></li>';
                }
                echo '</ul></div>';
            }

            // Get game tags
            $tags = $gameRepository->getTagsFromGame($game['g_id']);
            if ($tags) {
                echo '<div class="tagbox"><p class="tags" style="line-height: 40px;">Tags: ';
                echo displayTags($tags, true);
                echo '</p></div>';
            }
            ?>

            <script type="text/javascript">
            us_config = {
                container: 'messages',
                venue: 'game-<?= $game['user_id'] ?>_<?= $game['g_id'] . '-' . $game['author'] ?>',
                venue_container: 'venue',
                venue_type: 'game',
                owner: '<?= $isModerator ? $_SESSION['username'] : $game['author'] ?>',
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
            <?php
            if (!$isolated && $game['comments'] == 1) {
            ?>
            <a id="messages_top"></a>
            <div id="messages"></div>
            <?php } ?>
            <div id="venue" class="mprofvenue"></div>
            <script type="text/javascript" src="/comments/venue7.js"></script>
            <div class="spacer">&nbsp;</div>

            <br>
            <?php
                $db = getDatabase();

                $result = $db->query("SELECT g_id, first_published_date, title, author, views
                    FROM games
                    WHERE author = :author
                    AND isprivate = 0
                    AND ispublished = 1
                    AND isdeleted = 0
                    ORDER BY first_published_date DESC LIMIT 11", [
                    ':author' => $game['author']
                    ]);

                // remove game if id is same as current game
                foreach ($result as $key => $value) {
                    if ($value['g_id'] == $game['g_id']) {
                        unset($result[$key]);
                    }
                }
                $total_more_games = count($result);
                if ($total_more_games == 11) {
                    unset($result[11]);
                }
                if ($total_more_games != 0) {
                    ?>

            <div class="bucket moregames">


                <h5>More games by <a href="/members/index.php?u=<?= $game['author']?>"><?= $game['author'] ?></a></h5>

                <ul class="ratings_list">
                    <?php
                    //show games
                    foreach ($result as $more_game) {
                        echo '<li><a href="play.php?s=' . $game['user_id'] . '_' . $more_game['g_id'] . '">' . $more_game['title'] . '</a>&nbsp; <span class="viewscomments">' . date('m&\m\i\d\d\o\t;d&\m\i\d\d\o\t;y', strtotime($more_game['first_published_date'])) . ' &middot; ' . $more_game['views'] . ' views</span></li>';
                    }
                    ?>


                </ul>
            </div>
            <?php } ?>
            <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">



            <div class="gametypeinfo">
                <p>This is a game made with Sploder Revival's <a
                        href="../make/<?= $creator_type->url() ?>.php"><?= $creator_type->name() ?> game creator</a>.
                </p>
            </div>

            <div id="events" style="width: 260px; height: 480px; <?php
            $marginTop = 20;
            if ($showPrompt){
                $marginTop = $marginTop + 70;
            }
            if ($challengePrompt){
                $marginTop = $marginTop + 40;
            }
            if ($isEditorOrReviewer && $game['author'] != $_SESSION['username'] && $game['isprivate'] == 0) {
                $marginTop = $marginTop - 75;
            }
            echo 'margin-top: '.$marginTop.'px;';
            ?>">
            <div id="events_ticker"></div>
	        </div>


            <script type="text/javascript">
                swfobject.embedSWF("/swf/events7.swf", "events_ticker", "260", "480", "9", "/swfobject/expressInstall.swf", { PHPSESSID: "<?php
                if(isset($_SESSION['loggedin']) && $game['isprivate'] == 0){
                 echo $_SESSION['PHPSESSID'].'",';
                 echo 'u: "'.$_SESSION['username'];
                }?>" }, { bgcolor: "#000000", menu: "false", quality: "low", scale: "noscale", salign: "tl", wmode: "opaque" });
            </script>
    

            <br /><br />
            <?php
            include('../php/includes/votes.php');
            $votes = get_votes($game['g_id']);
            $total = $votes['count'];
            if ($total != 0) {
                $average = round($votes['avg'], 1);
                ?>

            <div class="promo gamerating">
                <div xmlns:v="https://rdf.data-vocabulary.org/#" typeof="v:Review-aggregate">
                    <span property="v:itemreviewed"><?= $game['title'] ?></span>
                    <span rel="v:rating">
                        <span typeof="v:Rating">
                            Rating:
                            <span property="v:average" datatype="xsd:string"><?= $average ?></span>
                            /
                            <span property="v:best" datatype="xsd:string">5</span>
                        </span>
                    </span>
                    based on
                    <span property="v:votes" datatype="xsd:string"><?= $total ?></span> rating<?php if ($total != 1) {
                        echo 's';
                                                                   } ?>
                </div>
                <div class="spacer"></div>
            </div>
            <?php } ?>


            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php'); ?>

</body>

</html>
