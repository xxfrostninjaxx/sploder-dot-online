<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
session_start();
require_once(__DIR__ . '/../repositories/repositorymanager.php');
$graphicsRepository = RepositoryManager::get()->getGraphicsRepository();
$graphicTags = $graphicsRepository->getGraphicTags($_GET['offset'] ?? 0,100);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php') ?>
    <link rel="stylesheet" type="text/css" href="/css/sploder_v2p22.min.css" />
    <link rel="stylesheet" type="text/css" href="/css/tags.css" />
    <script type="text/javascript">
    var _sf_startpt = (new Date()).getTime()
    </script>
    <script type="text/javascript">window.rpcinfo = "Viewing Graphic Tags";</script>
    <?php include(__DIR__ . '/../content/onlinechecker.php'); ?>
</head>
<?php include('../content/addressbar.php'); ?>

<body id="everyones">
    <?php include('../content/headernavigation.php') ?>
    <div id="page">
        <?php include('../content/subnav.php') ?>
        <div id="content">
            <h3>Tags</h3>
            <h4>Why Tag Your Graphics?</h4>
            <p>Tags help other people find your graphics for use in their games.  They also help keep Sploder Revival neat and tidy, and make it more interesting and fun!</p>
            <div id="viewpage">
                <div class="tagbox">
                    <p class="tags" style="line-height: 40px;">Tags:
                        <?php
                        require_once('../content/taglister.php');
                        echo displayTags($graphicTags->data, true, TagType::Graphics);
                        ?>
                    </p>

                    <?php require('../content/pages.php');
                    addPagination($graphicTags->total ?? 0, 100)
                    ?>
                </div>
                <div class="spacer">&nbsp;</div>
            </div>
        </div>
        <div id="sidebar">
            <br /><br /><br />
            <div class="spacer">&nbsp;</div>
        </div>
        <div class="spacer">&nbsp;</div>
        <?php include('../content/footernavigation.php') ?>
</body>

</html>