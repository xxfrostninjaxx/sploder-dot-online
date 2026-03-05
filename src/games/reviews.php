<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php session_start(); ?>
<?php
require_once('../repositories/repositorymanager.php');
require_once('../content/pages.php');
$gameRepository = RepositoryManager::get()->getGameRepository();
$perPage = 6;
if (isset($_GET['mine'])) {
    $reviews = $gameRepository->getAllReviewsByUsername($_SESSION['username'], $_GET['o'] ?? 0, $perPage);
} else {
    $reviews = $gameRepository->getPublicReviews($_GET['o'] ?? 0, $perPage);
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/reviews.css" />
    <link rel="stylesheet" type="text/css" href="/css/allreviews.css" />
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Viewing Reviews";</script>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones" class="reviews">

    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>


        <div id="content">
            <h3>Game Reviews</h3>
            <?php if (isset($_GET['deleted'])) : ?>
                <p class="prompt">Review deleted successfully!</p>
            <?php endif; ?>
            <p>This is the <strong>game reviews hub</strong> where you can find reviews of
        	some of your favorite games.  If you're interested in becoming a reviewer, let us know in the <a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>" target="_blank">discord server</a>! Now, on to the main attraction...</p>
            <?php
            $userRepository = RepositoryManager::get()->getUserRepository();
            $perms = $userRepository->getUserPerms($_SESSION['username'] ?? '');
            if ($perms !== null && $perms !== '' && str_contains($perms, 'R')){ ?>
                <a href="<?= isset($_GET['mine']) ? 'reviews.php' : 'reviews.php?mine' ?>"><input style="margin-top:-20px;" type="button" class="postbutton" value="<?= isset($_GET['mine']) ? 'All' : 'My' ?> reviews" /></a><br><br>
            <?php } ?>
            <?php
            if ($reviews->totalCount == 0) {
                if (isset($_GET['mine'])) {
                    echo '<p class="prompt">You have not written any reviews yet. To write a review, go to a games and click "Write Review".</p>';
                } else {
                    echo '<p class="prompt">There are no reviews available at this time.</p>';
                }
            }
            ?>
            <?php
                foreach ($reviews->data as $review) :
                    $reviewTitle = htmlspecialchars($review['title']);
                    $reviewLink = "view-review.php?s=" . $review['game_author_id'] . "_" . $review['g_id'] . "&userid=" . $review['userid'];
                    $reviewerPage = "/members/index.php?u=" . urlencode($review['author']);
                    $reviewerUsername = htmlspecialchars($review['author']);
                    $reviewDate = date("l, F jS Y", strtotime($review['review_date']));
                    $gameLink = "view-review.php?s=" . $review['game_author_id'] . "_" . $review['g_id'] . "&userid=" . $review['userid'];
                    $gameThumbUrl = "/users/user" . $review['game_author_id'] . "/images/proj" . $review['g_id'] . "/image.png";
                    $gameAuthor = htmlspecialchars($review['author']);
                    $reviewText = htmlspecialchars($review['review']);
                    
            ?>
                <div class="game">
					<h4><a href="<?= $reviewLink ?>"><?= $reviewTitle ?></a></h4>
                    <?php
                    if (($review['ispublished'] ?? true)== false) {
                        echo '<p class="alert">Unpublished</p>';
                    }
                    ?>
					<cite>Review by <a href="<?= $reviewerPage ?>"><?= $reviewerUsername ?></a> on <?= $reviewDate ?></cite>
					<div class="smallthumb">
						<a class="thumb" href="<?= $gameLink ?>">
						<img src="<?= $gameThumbUrl ?>" width="80" height="80" alt="Click to play <?= $gameAuthor ?>"/>
						</a>
					</div>
					<p><?= $reviewText ?></p>
					<p class="postlink" align="right"><a href="<?= $reviewLink ?>">Read and comment &raquo;</a></p>

					<div class="spacer">&nbsp;</div>
					<hr/>
				</div>
            <?php
                endforeach;
            ?>
            
            <div class="spacer">&nbsp;</div>
            <?php
                addPagination($reviews->totalCount, $perPage, $_GET['o'] ?? 0);
            ?>
        </div>
        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>