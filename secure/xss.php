<?php
// secure/xss.php - SECURE VERSION (Final Fixed)
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Guestbook - Secure (XSS Fixed)</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6; 
        }
        .comment {
            background: #f9f9f9;
            padding: 15px;
            margin: 12px 0;
            border-left: 5px solid #4caf50;
            border-radius: 4px;
        }
        .info {
            background: #e8f5e9;
            padding: 20px;
            border: 1px solid #4caf50;
            border-radius: 8px;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <h2>Guestbook (Secure - XSS Fixed)</h2>

    <form method="POST">
        Your Name: <input type="text" name="name" required><br><br>
        Comment: <textarea name="comment" rows="4" cols="50" required></textarea><br><br>
        <input type="submit" value="Post Comment">
    </form>

    <br><br>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $name = trim($_POST['name'] ?? '');
        $comment = trim($_POST['comment'] ?? '');

        $safe_name = htmlspecialchars($name, ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $safe_comment = htmlspecialchars($comment, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (!isset($_SESSION['comments'])) {
            $_SESSION['comments'] = [];
        }

        $_SESSION['comments'][] = [
            'name'    => $safe_name,
            'comment' => $safe_comment
        ];
    }

    // Clear comments
    if (isset($_GET['clear'])) {
        unset($_SESSION['comments']);
        if (ob_get_level() > 0) {
            ob_end_clean();
        }
        header("Location: xss.php");
        exit();
    }

    // Display comments safely
    if (!empty($_SESSION['comments'])) {
        echo "<h3>Comments:</h3>";
        foreach ($_SESSION['comments'] as $c) {
            if (is_array($c) && isset($c['name'])) {
                $display_name = $c['name'];
                $display_comment = $c['comment'];
            } else {
                // Fallback for any old string comments
                $display_name = "Anonymous";
                $display_comment = htmlspecialchars((string)$c, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            }

            echo "<div class='comment'>";
            echo "<strong>" . $display_name . ":</strong> " . $display_comment;
            echo "</div>";
        }
    }
    ?>

    <div class="info">
        <strong>Security Features Applied (XSS Fixed):</strong><br>
        • All user input is escaped using htmlspecialchars() with ENT_QUOTES | ENT_HTML5<br>
        • Both stored and reflected XSS are prevented<br>
        • Safe handling of old and new comment formats
    </div>

    <p>
        <a href="xss.php?clear=1">
            <button style="background:#4caf50; color:white; border:none; padding:10px 16px; border-radius:4px; cursor:pointer;">
                Clear All Comments
            </button>
        </a>
    </p>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>