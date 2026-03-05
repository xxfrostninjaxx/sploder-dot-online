<?php
function addPagination(int $total = 0, int $perPage = 12, int $offset = 0): void
{
    $params = $_SERVER['QUERY_STRING'];
    $queryParams = [];
    parse_str($params, $queryParams);
    unset($queryParams['o']);

    ?>
    <div class="pagination">
        <?php
        $queryString = http_build_query($queryParams);

        $totalPages = ceil($total / $perPage);
        $currentPage = $offset <= 0
            ? 1
            : $offset + 1;
        $start = max(1, min($currentPage - 3, $totalPages - 6));
        $end = min($totalPages, $start + 6);
        if ($total != 0) {
            if ($currentPage < $totalPages) {
                echo '<a href="?' . $queryString . '&o=' . ($currentPage) . '" class="page_button page_next">next &raquo;</a>';
            } else {
                echo '<span style="width:52px" class="page_button page_next">next &raquo;</span>';
            }
            for ($i = $end; $i >= $start; $i--) {
                if ($i == $currentPage) {
                    echo '<span class="page_button page_current">' . $i . '</span>';
                } else {
                    echo '<a href="?' . $queryString . '&o=' . ($i - 1) . '" class="page_button">' . $i . '</a>';
                }
            }
            if ($currentPage > 1) {
                echo '<a href="?' . $queryString . '&o=' . ($currentPage - 2) . '" class="page_button">&laquo;</a>';
            } else {
                echo '<span class="page_button page_button_inactive">&laquo;</span>';
            }
            echo '<br>';
        }
        ?>
    </div>
    <?php
}
