<?php

trigger_error("onlinecheck.php has been deprecated. Use onlinechecker.php for javascript creation, 
  and use logincheck.php for ensuring the session has been started", E_USER_DEPRECATED);

//Check whether session is already started, if not start the session
if (session_status() !== PHP_SESSION_ACTIVE) {
    //There is no active session
    session_start();
}

if (isset($_SESSION['username'])) {
    if (!isset($status)) {
        $status = "online";
    }
    ?>
<script>
fetch("/php/idlecheck.php")

function checkonline() {
    fetch("/php/online.php?status=<?= $status ?>")
}
var checkonline = setInterval(checkonline, 10000);
</script>
<?php } ?>
