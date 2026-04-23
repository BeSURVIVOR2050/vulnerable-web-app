<?php
// vulnerable/login.php - VULNERABLE VERSION
// Intentionally vulnerable to SQL Injection for Task B demonstration
ob_start();
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - Vulnerable</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6;
        }
        .error { color: red; }
        .warning {
            background: #ffe6e6;
            padding: 15px;
            border: 1px solid #ff9999;
            border-radius: 5px;
            margin: 20px 0;
        }
        input[type="text"], input[type="password"] {
            padding: 8px;
            width: 300px;
        }
    </style>
</head>
<body>
    <h2>Login (Vulnerable Version)</h2>

    <?php
    require_once __DIR__ . '/../includes/db_connect.php';
    $conn = db_connect();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Keep this page intentionally vulnerable, but make the demo resilient:
        // In MySQL, `--` comments require whitespace after `--` or they won't start a comment.
        $normalize_mysql_dash_comment = static function (string $s): string {
            return preg_replace('/--(?!\s)/', '-- ', $s) ?? $s;
        };

        $username = $normalize_mysql_dash_comment((string) ($_POST['username'] ?? ''));
        $password = $normalize_mysql_dash_comment((string) ($_POST['password'] ?? ''));

        // VULNERABLE: SQL Injection - Direct string concatenation
        $sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password'";
        
        echo "<p style='color:#666; font-size:0.9rem;'><strong>Executed Query:</strong> " . htmlspecialchars($sql) . "</p>";

        try {
            $result = mysqli_query($conn, $sql);
        } catch (mysqli_sql_exception $e) {
            $result = false;
            $error = "SQL Error: " . $e->getMessage();
        }

        if ($result && mysqli_num_rows($result) > 0) {
            $_SESSION['user'] = $username;
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
    }
    ?>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label>Password:</label><br>
        <input type="text" name="password" required>   <!-- Password visible for demonstration -->
        <br><br>
        
        <input type="submit" value="Login">
    </form>

    <div class="warning">
        ⚠️ <strong>This login page is intentionally vulnerable to SQL Injection!</strong><br><br>
        
        <strong>Try these payloads in the Username field:</strong><br>
        • <code>' OR '1'='1</code> — Bypass login (classic SQL Injection)<br>
        • <code>' OR '1'='1 -- </code> — Bypass with comment<br>
        • <code>admin' -- </code> — Login as admin without password
    </div>

    <p><small>
        Note: This is the vulnerable version of the login page.<br>
        No input sanitization or prepared statements are used.<br>
        The password field is visible for easier testing.
    </small></p>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>