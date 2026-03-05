<?php
include(__DIR__.'/../config/env.php');
$discord = getenv("DISCORD_INVITE");
$forums = getenv("FORUMS_URL");
?>
<!DOCTYPE html
    PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>
    <title>Sploder</title>
    <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" href="../css/sploder.css" type="text/css" />
    <style type="text/css">
        h1 {
            display: none;
        }

        div.epicfail {
            margin: 60px auto 0 auto;
            width: 540px;
            border: 4px solid #999;
            background-color: #000;
            font-size: 15px;
            padding: 20px;
            text-align: left;
        }

        div.epicfail img {
            float: left;
            margin-right: 20px;
        }

        div.epicfoot {
            margin: 10px auto;
            color: #999;
        }

        div.epicfoot a {
            color: #fff;
            font-weight: normal;
        }
    </style>
    <script type="text/javascript">
        function make_title() {
            titles = ["Aw Snap!", "Epic Fail!", "Holy Splode!", "ZOMG!", "PWND.", "Don't You Believe it!", "To the moon, Alice!", "Oh no you di'int!", "Shut the front door!", "Lectroids!", "Failsauce.", "Yikes!"];
            return titles[Math.floor(Math.random() * titles.length)];
        }
    </script>
</head>

<body>
    <h1>Sploder</h1>
    <div class="epicfail">
        <img align="left" src="../images/ad_200x200.jpg" alt="boom!" />
        <h3>
            <script type="text/javascript">document.write(make_title());</script>
        </h3>
        <p>There is currently a problem with the server. Some functions and content are unavailable at this time.
            Please be patient as we are working on the problem and will have it fixed as soon as possible. Thanks!</p>
        <p><a href="javascript:history.back()">&laquo; go back whence ye came</a></p>
    </div>
    <div class="epicfoot">
        You can also try the <a target="_blank" href="<?= $forums ?>">forums</a> or yell at us on <a
            target="_blank" href="https://discord.com/invite/<?= $discord ?>">discord</a>.
    </div>
</body>

</html>