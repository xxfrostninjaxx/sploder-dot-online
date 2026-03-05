<?php
if (str_contains(($_SERVER['HTTP_USER_AGENT'] ?? 'nothing'), 'Electron')) {
    require_once(__DIR__ . '/../config/env.php');
    $domainName = getenv("DOMAIN_NAME");
?>

<div class="topnav">

    <div class="search-container">
        <form action="/php/url.php" method="post">
            <?php
                $script   = $_SERVER['SCRIPT_NAME'];
                $params   = $_SERVER['QUERY_STRING'];
                if ($params == "") {
                    $currentUrl = $domainName . $script;
                } else {
                    $currentUrl = $domainName . $script . '?' . $params;
                }

                ?>
            <input class="urlmessage" style="" type="text" value="<?php echo $currentUrl; ?>" name="url">
            <input style="" value="<?php echo $currentUrl . '?'; ?>" type="hidden" name="back">
            <?php if ($_GET["urlerr"] ?? null == 1) {
                    echo '<p style="margin-top:45px;" class="urlmessage">You must enter a valid ' . str_replace(['http://', 'https://'], "", $domainName) . ' url!</p><br><br><br>';
                } ?>
            <?php if ($_GET["errload"] ?? null == 1) {
                    echo '<p style="margin-top:45px;" class="urlmessage">There was an error accessing this URL!</p><br><br><br>';
                } ?>

        </form>
        <br><br><br>
    </div>
</div>
<?php } ?>