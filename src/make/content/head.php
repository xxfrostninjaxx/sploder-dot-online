<link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
<?php require('../content/swfobject.php'); ?>
<?php include('../content/head.php'); ?>
<?php require(__DIR__ . '/../../repositories/repositorymanager.php'); ?>
<?php
$userRepository = RepositoryManager::get()->getUserRepository();
if(isset($_SESSION['userid'])) {
    $level = $userRepository->getLevelByUserId($_SESSION['userid']);
} else {
    $level = 1;
}
?>


<script type="text/javascript">
var userid = "demo";

var page_start_time = (new Date()).getTime();



var popUpWin = 0;

function popUpWindow(URLStr, left, top, width, height)

{



    if (popUpWin)

    {

        if (!popUpWin.closed) popUpWin.close();

    }

    popUpWin = open(URLStr, '_blank',
        'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=yes,width=' +
        width + ',height=' + height + ',left=' + left + ', top=' + top + ',screenX=' + left + ',screenY=' +
        top + '');

}


function getPhotos() {

    //popUpWindow("php/uploadform.php?PHPSESSID=", Math.floor(screen.width / 2) - 275, Math.floor(screen.height / 2) -
    //    225, 550, 450);

}



function launchHelp() {

    popUpWindow("help_inline.php?&inLauncher=1", Math.floor(screen.width / 2) - 275, Math.floor(screen.height / 2) -
        225, 550, 450);

}



function playMovie(movieID, userID, creationDate, wide) {

    if ((wide == null) | (wide < 660)) {

        wide = 660;

    } else if (wide > screen.width) {

        wide = screen.width - 20;

    }

    popUpWindow("/php/player.php?m=" + movieID + "&u=" + userID + "&c=" + creationDate, Math.floor(screen
        .width / 2) - (wide / 2), Math.floor(screen.height / 2) - 200, wide, 400);

}



function playPubMovie(pubkey, wide) {

    if ((wide == null) | (wide < 660)) {

        wide = 660;

    } else if (wide > screen.width) {

        wide = screen.width - 20;

    }

    popUpWindow("publish.php?s=" + pubkey + "#kickdown", Math.floor(screen.width / 2) - (wide / 2), Math
        .floor(screen.height / 2) - 270, wide, 540);

}



function updateMovie(value) {

    var InternetExplorer = navigator.appName.indexOf("Microsoft") != -1;

    if (InternetExplorer) {

        document.creator.SetVariable("/browsermanager:callvalue", value);

        document.creator.TPlay("browsermanager");

    } else {

        document.creator.SetVariable("/browsermanager:callvalue", value);

        document.creator.TPlay("browsermanager");

    }

}

var flashvars = {
    v: "15",
    copyaction: "<?php
        if (isset($_GET['copyaction']) && $_GET['copyaction'] === 'true') {
            echo 'true';
        } else {
            echo 'false';
        }
        ?>",
    <?php if (!isset($_SESSION['username'])) { ?>
    PHPSESSID: "demo",
    userid: "demo",
    username: "demo",
    creationdate: "20070102003743"

    <?php } else { ?>
    PHPSESSID: "<?php echo $_SESSION['PHPSESSID']; ?>",
    userid: "<?php echo $_SESSION['userid'] ?>",
    username: "<?php echo $_SESSION['username'] ?>",
    creationdate: "<?php echo time() ?>",
    userlevel: "<?php echo $level ?>"
    <?php } ?>
};

var params = {
    menu: "false",
    quality: "high",
    scale: "noscale",
    salign: "tl",
    bgcolor: "#333333",
    base: ""
};