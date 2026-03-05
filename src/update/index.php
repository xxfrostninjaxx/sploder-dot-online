<?php
require_once '../content/initialize.php';
require(__DIR__.'/../config/env.php');
$version = file_get_contents('currentversion.txt');
$userVersion = explode('Sploder/', $_SERVER['HTTP_USER_AGENT'])[1];
$userVersion = explode(' ', $userVersion)[0];
if ($version == $userVersion) {
    header('Location: /');
    exit();
}
$info = explode('-', $_GET['info']);
$os = $info[0];
if ($os == 'win32') {
    $arch = $info[1];
    $method = $info[2];
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="update.css" />

    <script type="text/javascript">
        const version = '<?= $version ?>';
        const os = '<?= $os ?>';
        const arch = '<?= $arch ?? '' ?>';
        const method = '<?= $method ?? '' ?>';
        const repositoryUrl = '<?= getenv('LAUNCHER_REPOSITORY_URL') ?>';
    </script>
    <script src="update.js"></script>



</head>

<body id="home" class="">

    <div id="main">

        <div id="page">
            <div id="content">
                <div id="new_status" class="get_started">

                    <h4>Welcome to the Sploder Launcher Upgrade wizard</h4>
                    <p> Sploder Launcher has updated to version <code><?= $version ?></code>.<br>To continue using
                        Sploder Launcher, you must upgrade.<br><br>
                    </p>

                    <ul class="actions">
                        <a onclick="start_download()" href="#">
                            <li>
                                Download
                            </li>
                        </a>
                    </ul><br><br><br><br>
                    <div style="display:none" id="progress-container" class="progress-container">
                        <div class="progress-bar" id="progress-bar"></div>
                    </div>
                </div>

                <div class="finished" id="finished" style="display:none">
                    <b>
                        <p id="downloadCompleteMessage"></p>
                    </b>
                </div>


            </div>
        </div>
    </div>
</body>

</html>