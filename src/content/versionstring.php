<?php
$pageExecutionEndTime = microtime(true);
$pageExecutionTime = $pageExecutionEndTime - $pageExecutionStartTime;
$pageExecutionTime = number_format($pageExecutionTime, 3) . "s";
$peakMemoryUsage = number_format(memory_get_peak_usage() / 1024 / 1024, 2) . " MB";
?>
<div style="position: fixed; right: 0.7vw; bottom: 0.7vw; font-size: 0.8vw; opacity: 50%; z-index: 1000; text-align: right; cursor: default; user-select: none;">
    <span title="Page Execution Time"><?= $pageExecutionTime ?></span><br>
    <span title="Peak Memory Usage"><?= $peakMemoryUsage ?></span><br>
    <span title="Version">alpha v1.1</span>
</div>