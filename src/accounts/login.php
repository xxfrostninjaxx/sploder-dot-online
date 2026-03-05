<?php
require_once '../content/initialize.php';
require(__DIR__.'/../content/disablemobile.php'); ?>
<?php
require('logincheck.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML+RDFa 1.0//EN" "http://www.w3.org/MarkUp/DTD/xhtml-rdfa-1.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <?php include('../content/head.php'); ?>

    <link rel="stylesheet" type="text/css" href="splodersimple.css" />


    <?php include('../content/onlinechecker.php'); ?>
    <script type="text/javascript">window.rpcinfo = "Logging in";</script>
    <script type="text/javascript" src="login.js"></script>

</head>
<?php include('../content/addressbar.php'); ?>

<body id="home" class="">

    <div id="main">
        <div id="header">
            <a href="/">
                <div id="title">
                    <h1><a href="/" title="Sploder"><img style="margin-top:-20px; height: 130px"
                                src="/chrome/logo.png"><span class="hide">Games at Sploder</span></a></h1>
                </div>
                <div id="tools"></div>
            </a>
        </div>
        <div id="page">
            <div id="content">
                <h3>Sploder Revival Login</h3>
                <?php
                if (!(getenv('SWITCH') == 'true' || (getenv('SWITCH_TIMER') != 0 && getenv('SWITCH_TIMER') < time()))) {
                ?>
                <p>

                    Enter your login information below to sign in to Sploder Revival.

                </p>

                <?php
                if (isset($_GET['err'])) {
                    $err = $_GET['err'];
                }
                if (isset($err)) {
                    if ($err == "no") { ?>
                <p class="alert">The username and/or password was incorrect!</p>
                <?php }
                } ?>


                <p class="subdued">Not a member yet? <a target="_blank" href="register.php">Sign up here.</a></p>



                <form action="check.php" method="post">

                    <p>

                        <label for="login_username">Username:</label><br />

                        <input type="text" id="login_username" name="username" autocomplete="off" autocorrect="off"
                            autocapitalize="off" spellcheck="false" maxlength="20" required />



                        <input type="hidden" name="nexturl" value="" />



                        <label for="login_password">Password:</label><br />

                        <input type="password" id="login_password" name="password" size="12" maxlength="25"
                            onkeydown="e=window.event; if (e) if (e.keyCode == 13) this.form.submit(); return true;"
                            required />



                        <input type="submit" onclick="storeUsername()" name="Submit" class="postbutton loginbutton"
                            value="Log in " />

                    </p>

                </form>
                <?php
                } else {
                ?>
                <br>
                <p class="alert">
                    <?php
                    $switch_message = getenv('SWITCH_MESSAGE');
                    if ($switch_message) {
                        echo htmlspecialchars($switch_message);
                    }
                }
                ?>
                <div class="spacer">&nbsp;</div>
            </div>
            <div class="spacer">&nbsp;</div>
        </div>
    </div>
    <div id="bottomnav">
        <ul>
            <li><a href="/legal/termsofservice.php">Terms of Service</a></li>
            <li><a href="/credits.php">Credits</a></li>
            <li><a href="/legal/privacypolicy.php">Privacy Policy</a></li>
        </ul>
    </div>


<?php require_once(__DIR__.'/../content/versionstring.php'); ?>

</body>

</html>