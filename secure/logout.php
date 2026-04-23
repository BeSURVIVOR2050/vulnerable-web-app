<?php
// secure/logout.php - SECURE VERSION
// Simple and secure logout functionality
session_start();
// SECURITY: Destroy server-side session data (logs the user out).
session_destroy();

// SECURITY: Also clear the session cookie (defense-in-depth for some PHP setups).
if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

// Redirect to login page
header("Location: login.php");
exit();
?>