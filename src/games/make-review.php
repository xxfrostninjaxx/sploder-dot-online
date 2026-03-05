<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<?php
require_once('../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$perms = $userRepository->getUserPerms($_SESSION['username']);
if ($perms === null || $perms === '' || !str_contains($perms, 'R')) {
    die("Haxxor detected");
}
$s = $_GET['s'] ?? '';
if ($s == '') {
    header('Location: /games/reviews.php');
    die();
}
$s = explode("_", $s);
$userId = $s[0];
$gameId = $s[1];
$gameRepository = RepositoryManager::get()->getGameRepository();
$gameInfo = $gameRepository->getGameBasicInfo($gameId);
if ($gameInfo === null) {
    header('Location: /games/reviews.php');
    die();
}
$gameAuthor = $gameInfo['author'];
$gameTitle = $gameInfo['title'];
if (isset($_POST['reviewTitle'])) {
    require_once('../content/keyboardfilter.php');
    require_once('../content/censor.php');
    $title = censorText(trim($_POST['reviewTitle']));
    // Capitalize the first letter of each word in the title
    $title = filterKeyboard(censorText(ucwords(trim($title))), false);
    $body = filterKeyboard(censorText(trim($_POST['reviewBody'])), false);
    $titleLength = strlen($title);
    $bodyLength = strlen($body);
    if ($titleLength > 100) {
        $title = substr($title, 0, 100);
    }
    if ($bodyLength > 10000) {
        $body = substr($body, 0, 10000);
    }
    if ($titleLength < 5) {
        $err = "Title must be at least 5 characters long.";
    }
    if ($bodyLength < 50) {
        $err = "Review must be at least 50 characters long.";
    }

    $publish = isset($_POST['publishNow']) ? 1 : 0;
    if (!isset($err)) {
        $gameRepository->saveReview($_SESSION['userid'], $gameId, $title, $body, $publish);
        $prompt = $publish ? "Review published successfully!" : "Review saved successfully! To see a preview, click <a href='view-review.php?s={$userId}_{$gameId}&userid={$_SESSION['userid']}'>here</a>.";
    }
}
$reviewData = $gameRepository->getReviewData($_SESSION['userid'], $gameId);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/reviews.css" />
    <!-- <link rel="stylesheet" type="text/css" href="/css/allreviews.css" /> -->
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Writing a Review";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="reviews">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Game Review Editor</h3>
            <p>This is the <strong>game reviews hub</strong> where you can find reviews of
            some of your favorite games.  If you're interested in becoming a reviewer, let us know in the <a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>" target="_blank">discord server</a>! Now, on to the main attraction...</p>

            <?php
            if (isset($prompt)) {
                echo '<p class="prompt">' . $prompt . '</p>';
            }
            if (isset($err)) {
                echo '<p class="alert">' . htmlspecialchars($err) . '</p>';
            }
            ?>

            <div class="game">
                    <div class="smallthumb">
                        <a class="thumb" href="play.php?s=<?= $userId ?>_<?= $gameId ?>">
                        <img src="/users/user<?= $userId ?>/images/proj<?= $gameId ?>/thumbnail.png" width="80" height="80"/>
                        </a>
                    </div><br>
                    <p>You are now writing a review for the game <a href="play.php?s=<?= $userId ?>_<?= $gameId ?>"><?= $gameTitle ?></a> by <a href="../members/?u=<?= $gameAuthor ?>"><?= $gameAuthor ?></a>. You can click "Save" at any time to save your work.
                    It won't be published until you also check the "Publish Now" checkbox. You can also unpublish by unchecking the box and saving. Be sure to provide
                    a fair, constructive review!</p>				
                    <div class="spacer">&nbsp;</div>
                    <hr style="margin-top:-1px;"/>
                </div>
            
            <div class="spacer">&nbsp;</div>
        <!-- Review Form UI -->
        <form id="reviewForm" method="post" action="">
            <label for="reviewTitle"><span style="font-weight:bold;">Review Title:</span></label><br />
            <input type="text" id="reviewTitle" name="reviewTitle" value="<?= $reviewData['title'] ?? '' ?>" required maxlength="100" style="width: 75%;"/><br /><br />
            <label for="reviewBody"><span style="font-weight:bold;">Review Body:</span> <span style="color:#aaa">(HTML is removed. ENTER twice for new paragraph.)</span></label><br />
            <textarea id="reviewBody" name="reviewBody" rows="17" required maxlength="10000" style="width: 100%; resize: none;"><?= $reviewData['review'] ?? '' ?></textarea><br /><br />
            <input type="submit" class="postbutton" value="Submit Review"></input>
            &nbsp;
            <input type="checkbox" id="publishNow" name="publishNow" <?= $reviewData['ispublished'] ?? false ? 'checked' : '' ?>> Publish Now</input>
            <!-- Code syntax -->
            <div class="codeSyntax" style="position: relative; float: right; margin; font-size: 13px; width: 200px; padding: 12px 12px 10px 12px; border: 3px solid #aaa;">
            <span style="position: absolute; top: -13px; left: 12px; background: #000000; padding: 0 6px; margin-top: 3px;">
                Code Syntax
            </span>
            <div>
                Rating: ******<br />
                <span>**Large Heading**</span><br />
                <span>*Bold Heading*</span><br />
                <span>~Italic Heading~</span>
            </div>
            </div>
                    
        </form>
        <div class="spacer">&nbsp;</div>
        </div>
        <div id="sidebar">

            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>