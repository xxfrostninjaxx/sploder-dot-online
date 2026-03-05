<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
include('../content/logincheck.php');
$username = $_SESSION['username'];
require_once('../services/GameListRenderService.php');
require_once('../repositories/repositorymanager.php');

$gameRepository = RepositoryManager::get()->getGameRepository();
$gameListRenderService = new GameListRenderService($gameRepository);

if (isset($_GET['game']) && $_GET['game'] == null) {
    unset($_GET['game']);
}


$totalgames = $gameRepository->getTotalDeletedGameCount($username);
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
    <?php include('../content/onlinechecker.php'); ?>
    <script>
        function delproj(id, title) {
            let text;
            if (confirm(("Are you REALLY REALLY sure you want to delete " + title +
                    "\nRemember, permanently deleted games can NEVER be recovered, not even by the developers.")) == true) {
                location.href = ("../php/permadelete.php?id=" + id);
            } else {}
        }
    </script>
    <script>
        function resproj(id, title) {
            let text;
            if (confirm(("Are you sure you want to restore " + title)) == true) {
                location.href = ("../php/restore.php?id=" + id);
            } else {}
        }
    </script>
    <script type="text/javascript">window.rpcinfo = "Managing their Games";</script>
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
            <h3>Deleted Games</h3>
            <p>Here's a list of all your deleted games. Remember, permanently deleted games can NEVER be recovered, not
                even by the developers.</p>


            <form action="trash.php" method="GET"><label for="title">Search by title:
                    &nbsp;</label><input style="width:98.5%;height:26px" placeholder="My deleted game" value=<?php if (isset($_GET['game'])) {
                                                                                                                    echo json_encode($_GET['game']);
                                                                                                              } else {echo '""';} ?>
                    class="urlthing" type="text" id="game" name="game" autocomplete="off" autocorrect="off"
                    autocapitalize="off" spellcheck="false" maxlength="100" /><br><br><br></form>
                    <?php
                    if ($totalgames == 0) {
                        echo "You have not deleted any games so far!";
                    } else {
                        $currentPage = isset($_GET['o']) ? (int)$_GET['o'] : 0;
                        $perPage = 12;
                        if (isset($_GET['game'])) {
                            $total = $gameListRenderService->renderPartialViewForMyGamesUserAndGame(
                                $username,
                                $_GET['game'],
                                $currentPage,
                                $perPage,
                                true
                            );
                        } else {
                            $total = $gameListRenderService->renderPartialViewForMyGamesUser(
                                $username,
                                $currentPage,
                                $perPage,
                                true
                            );
                        }
                    }
                    ?>
                <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
    </div>
</body>

</html>
