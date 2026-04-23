<?php
// vulnerable/xss.php - VULNERABLE VERSION (with Clear Comments button)
// Intentionally vulnerable to XSS for Task B demonstration
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Vulnerable (XSS)</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6; 
        }
        .comment {
            background: #fff3e0;
            padding: 15px;
            margin: 12px 0;
            border-left: 5px solid #ff9800;
            border-radius: 4px;
        }
        .warning {
            color: #d32f2f;
            background: #ffe6e6;
            padding: 20px;
            border: 1px solid #ff9999;
            border-radius: 8px;
            margin: 25px 0;
        }
        button { margin: 5px; padding: 8px 16px; }
    </style>
</head>
<body>
    <h2>Guestbook (Vulnerable - XSS)</h2>

    <form method="POST">
        Your Name: <input type="text" name="name" required><br><br>
        Comment: <textarea name="comment" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="Post Comment">
    </form>

    <br><br>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = $_POST['name'] ?? '';
        $comment = $_POST['comment'] ?? '';

        // VULNERABLE: No escaping → Stored XSS
        if (!isset($_SESSION['comments'])) {
            $_SESSION['comments'] = [];
        }
        $_SESSION['comments'][] = "<strong>" . $name . ":</strong> " . $comment;
    }

    // Clear comments if requested
    if (isset($_GET['clear'])) {
        unset($_SESSION['comments']);
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header("Location: xss.php");
        exit();
    }

    // Display comments without escaping
    if (!empty($_SESSION['comments'])) {
        echo "<h3>Comments:</h3>";
        foreach ($_SESSION['comments'] as $c) {
            echo "<div class='comment'>" . $c . "</div>";
        }
    }
    ?>

    <div class="warning">
        ⚠️ <strong>This page is intentionally vulnerable to Cross-Site Scripting (XSS)!</strong><br><br>
        
        <strong>Attack Examples (Task B):</strong><br>
        • <code>&lt;script&gt;alert('XSS Attack!')&lt;/script&gt;</code><br>
        • <code>&lt;img src=x onerror=alert('XSS')&gt;</code><br>
        • <code>&lt;script&gt;alert(document.cookie)&lt;/script&gt;</code>
    </div>

    <p>
        <a href="xss.php?clear=1">
            <button style="background:#f44336; color:white; border:none; cursor:pointer;">
                Clear All Comments
            </button>
        </a>
    </p>

    <p><small>
        Note: This version deliberately does not escape any user input.<br>
        For Task B demonstration only.
    </small></p>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>