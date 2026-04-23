<?php
// vulnerable/logout.php - VULNERABLE VERSION
// Simple logout functionality (for vulnerable demonstration)
session_start();
session_destroy();   // Destroy all session data

// Redirect to login page
header("Location: login.php");
exit();
?>