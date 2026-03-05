<?php
ob_start();

// Measure page execution time
$pageExecutionStartTime = microtime(true);

$GLOBALS['has_page_warning'] = false;

function custom_warning_handler($errno, $errstr, $errfile, $errline) {
    if ($errno & (E_WARNING | E_NOTICE | E_DEPRECATED)) {
        $GLOBALS['has_page_warning'] = true;
    }
    return false;
}

if (getenv('PHP_ENVIRONMENT') === 'development') {
    set_error_handler('custom_warning_handler');
    if ((getenv('SWITCH') == 'true' || (getenv('SWITCH_TIMER') != 0 && getenv('SWITCH_TIMER') < time()))) {
        // Logout
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        session_regenerate_id(true);
        session_destroy();
    }
}

// Add an error handler that catches fatal errors
// This checks if headers have been sent,
// If it is, it will attempt to add javascript
// that replaces the HTML content with a 500 error message
// else, just load the error 500 template and serve it directly
// Add an error handler that catches fatal errors
register_shutdown_function('error_handler');

function error_handler() {
    // If development, return
    if (getenv('PHP_ENVIRONMENT') === 'development') {
        return;
    }
    $last_error = error_get_last();
    // Check if the last error is a fatal error
    if ($last_error && in_array($last_error['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR])) {
        ob_clean();
        // Capture the output of err50x.php into a variable
        ob_start();
        require(__DIR__ . '/../error_pages/err50x.php');
        $error_page_content = ob_get_clean();

        // Check if headers have been sent
        if (headers_sent()) {
            // Headers sent, use JavaScript to replace content with the captured HTML
            echo '<script type="text/javascript">
    document.open();
    document.write(' . json_encode($error_page_content) . ');
    document.close();
</script>';
        } else {
            // Headers not sent, set a 500 status code and output the captured content
            http_response_code(500);
            echo $error_page_content;
        }
    }
    if ($GLOBALS['has_page_warning']) {
        $output = ob_get_clean();
        $warning_message = ' <div style="background-color: black; color: yellow; padding: 15px; border-color: gray; border-style: solid; border-width: 2px; font-weight: bold;">
We noticed that there is an issue with this page! There is currently a problem with the server. Some functions and content are unavailable at this time.
Please be patient as we are working on the problem and will have it fixed as soon as possible. Thanks!
</div>';
        $output = str_replace('<!-- Sploder Home Page Top Banner -->', $warning_message, $output);
        ob_start();
        echo $output;
    }
    ob_end_flush();
}
?>