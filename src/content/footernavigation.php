<div id="footer">

    <div class="spacer">&nbsp;</div>
</div>
<div class="spacer">&nbsp;</div>
</div>
</div>
<div id="bottomnav">
    <ul>
        <li><a href="/">Home</a></li>
        <li><a href="/legal/termsofservice.php">Terms of Service</a></li>
        <li><a href="/credits.php">Credits</a></li>
        <li><a href="/legal/privacypolicy.php">Privacy Policy</a></li>
        <li><a href="https://discord.com/invite/<?= getenv('DISCORD_INVITE') ?>/" target="_blank">Discord</a></li>
    </ul><br><br><i>All assets are the property of neurofuzzy
        <?php
        $public = getenv('NETWORKED');
        if($public == 'true') {
            $repository = getenv('REPOSITORY');
            echo " | <a href='$repository' target='_blank'>Source code</a>";
        }
        ?>
    </i>
    <?php require_once(__DIR__.'/../content/versionstring.php'); ?>
</div>