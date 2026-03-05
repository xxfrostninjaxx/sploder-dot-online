<?php
if (isset($_SESSION['username'])) {
    if (!isset($status)) {
        $status = "online";
    }
?>
    <script>
        fetch("/php/idlecheck.php")

        function checkonline() {
            fetch("/php/online.php?status=<?= $status ?>");
        }
        var checkonline = setInterval(checkonline, 10000);
    </script>
<?php } ?>