<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php include('../content/logincheck.php'); ?>
<?php
require_once('../repositories/repositorymanager.php');
$userRepository = RepositoryManager::get()->getUserRepository();
$isolated = $userRepository->isIsolated($_SESSION['username']) || $userRepository->isIsolated($_GET['u'] ?? '');
$userInfo = $userRepository->getUserInfo($_SESSION['username']);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>
    <link href="css/css.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" type="text/css" href="../css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="../css/members.css" />
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
        <!-- //
        var flashLoaded = false;

        function doLoad() {
            if (!flashLoaded) {
                try {
                    so.write("flashcontent");
                } catch (e) {}
                flashLoaded = true;
            }
        }

        setTimeout("doLoad()", 2000);

        //
        -->
    </script>
    <?php include('../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Editing Profile Information";</script>
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
            <h3>Edit Profile</h3>
            <div id="venue" style="margin: -30px 0 -5px 20px; float: right;"></div>



            <p>Enter some info about you that you would like to appear on your profile.<br>This information will be
                visible to everyone on your public profile. Leave a field <strong>blank</strong> if you do not wish for
                it to appear on your profile.</p>


            <div class="buttons" style="padding: 0;">
                <span class="button firstbutton"><a href="/accounts/avatar.php">Edit your avatar
                        &raquo;</a></span>&nbsp;
                <span class="button"><a href="/members/index.php?u=<?php echo $_SESSION['username'] ?>">View your public
                        profile &raquo;</a></span>&nbsp;
                <br><br>
            </div>

            <div class="spacer">&nbsp;</div>

            <div class="spacer">&nbsp;</div>


            <form action="profile-update.php" method="post" onsubmit="return filterProfileFields(this);">
                <script type="text/javascript">
                function filterProfileFields(form) {
                    // Regex: only allow standard keyboard characters
                    var allowedWithNewlines = /^[a-zA-Z0-9_ !@#$%^&*();\/|<>"'+.,:?=\-\[\]\n\r]*$/;
                    var allowedNoNewlines = /^[a-zA-Z0-9_ !@#$%^&*();\/|<>"'+.,:?=\-\[\]]*$/;

                    // Description: allow newlines
                    var descVal = form.description.value;
                    if (!allowedWithNewlines.test(descVal)) {
                        alert("Description contains invalid characters. Only standard keyboard characters (no emojis) are allowed.");
                        form.description.focus();
                        return false;
                    }

                    // All other fields: remove newlines, then test
                    var otherFields = [
                        form.hobbies,
                        form.favoriteSports,
                        form.favoriteGames,
                        form.favoriteMovies,
                        form.favoriteBands,
                        form.whomIRespect
                    ];
                    for (var i = 0; i < otherFields.length; i++) {
                        var val = otherFields[i].value.replace(/[\n\r]+/g, ' ');
                        otherFields[i].value = val;
                        if (!allowedNoNewlines.test(val)) {
                            alert("One or more fields contain invalid characters. Only standard keyboard characters (no emojis) are allowed.");
                            otherFields[i].focus();
                            return false;
                        }
                    }
                    return true;
                }
                </script>
                <label for="description">Description:</label><br><br>
                <textarea id="description" name="description" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter a description about yourself..."><?= $userInfo['description'] ?? '' ?></textarea><br><br><br>
                <label for="hobbies">Hobbies:</label><br><br>
                <textarea id="hobbies" name="hobbies" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter your hobbies..."><?= $userInfo['hobbies'] ?? '' ?></textarea><br><br><br>
                <label for="favoriteSports">Favorite Sports:</label><br><br>
                <textarea id="favoriteSports" name="favoriteSports" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter your favorite sports..."><?= $userInfo['sports'] ?? '' ?></textarea><br><br><br>
                <label for="favoriteGames">Favorite Games:</label><br><br>
                <textarea id="favoriteGames" name="favoriteGames" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter your favorite games..."><?= $userInfo['games'] ?? '' ?></textarea><br><br><br>
                <label for="favoriteMovies">Favorite Movies:</label><br><br>
                <textarea id="favoriteMovies" name="favoriteMovies" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter your favorite movies..."><?= $userInfo['movies'] ?? '' ?></textarea><br><br><br>
                <label for="favoriteBands">Favorite Bands:</label><br><br>
                <textarea id="favoriteBands" name="favoriteBands" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter your favorite bands..."><?= $userInfo['bands'] ?? '' ?></textarea><br><br><br>
                <label for="whomIRespect">Whom You Respect:</label><br><br>
                <textarea id="whomIRespect" name="whomIRespect" rows="3" style="width: 100%; resize: none;" maxlength="500"
                    placeholder="Enter whom you respect..."><?= $userInfo['respect'] ?? '' ?></textarea><br><br>
                <input type="checkbox" id="isolate" name="isolate" <?php if(!$isolated) { echo 'checked'; } ?>>
                <label for="isolate">Allow comments and friending <?php if(!$isolated) {echo '<br>Warning! Disabling this option will permanently ERASE all your current friends.'; } ?></label><br><br>
                
                <input type="submit" value="Submit" style="height: 40px" class="postbutton">
            </form>
        </div>
        <div id="sidebar">

            <?php include('../content/onlinelist.php') ?>


            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div><?php include('../content/footernavigation.php') ?>
</body>

</html>