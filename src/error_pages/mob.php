
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php
require_once '../content/initialize.php';
 include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="../update/update.css" />
    <!-- Mobile device support -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">

</head>

<body id="home" class="">

    <div id="main">

        <div id="page" style="height:100%;">
            <div id="content">
                <div id="new_status" class="get_started">
                    
                    <h4>Well, we've hit a problem!</h4>
                    <p>It seems like you're using a mobile device, which is not supported by our website.<br>
                        Please use a desktop or laptop computer with either Windows, macOS or Linux to access our website.
                    </p>

                    <ul class="actions">
                        <a onclick="history.back()" href="#">
                            <li>
                                Go Back
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