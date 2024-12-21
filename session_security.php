<?php
function check_session() {
    // Check if user is logged in
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    // Check session timeout (30 minutes)
    $timeout = 1800; // 30 minutes in seconds
    if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > $timeout) {
        session_unset();     // remove all session variables
        session_destroy();    // destroy the session
        header("Location: login.php?msg=timeout");
        exit();
    }

    // Update last activity time
    $_SESSION['last_activity'] = time();
}

function check_admin() {
    // Check if user is admin
    if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'admin') {
        header("Location: login.php");
        exit();
    }
}
?>