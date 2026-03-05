<div id="subnav">
    <ul class="nav_games">
        <li><a href="/">Home</a></li>
        <li><a href="index.php">Moderation</a></li>
        <li><a href="pending.php">Pending deletion</a></li>
        <li><a href="ipcheck.php">IP check</a></li>
        <li><a href="banned.php">Banned members</a></li>
        <li><a href="logs.php">Logs</a></li>
        <?php
            require_once(__DIR__."/../php/admincheck.php");
            $isAdmin = isAdmin($_SESSION['username']);
            if ($isAdmin) {
                echo '<li style="float:right"><a href="admin.php">Administration</a></li>';
            }
        ?>
    </ul>
</div>