<?php
// secure/login.php - SECURE VERSION
// SQL Injection fixed with Prepared Statements
// Password field is visible for demonstration purposes
ob_start();
session_start();
// SECURITY: Session-based auth — login sets `$_SESSION['user']` after successful verification.
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Login</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6;
        }
        .error { color: red; }
        .info {
            background: #e6ffe6;
            padding: 15px;
            border: 1px solid #99cc99;
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
    <h2>Login (Secure Version)</h2>

    <?php
    require_once __DIR__ . '/../includes/db_connect.php';
    $conn = db_connect();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);

        // SECURITY: Prepared statement — this is the key row that prevents SQL Injection in login.
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
        // SECURITY: Bind parameters — never concatenate `$username` / `$password` into SQL.
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            // SECURITY: Store only the minimal identity needed in session (avoid storing raw password).
            $_SESSION['user'] = $username;
            if (ob_get_level() > 0) {
                ob_end_clean();
            }
            header("Location: dashboard.php");
            exit();
        } else {
            $error = "Invalid username or password";
        }
        $stmt->close();
    }
    ?>

    <?php if (isset($error)) echo "<p class='error'>$error</p>"; ?>

    <form method="POST">
        <label>Username:</label><br>
        <input type="text" name="username" required><br><br>
        
        <label>Password:</label><br>
        <input type="text" name="password" required>   <!-- Password is visible for demonstration -->
        <br><br>
        
        <input type="submit" value="Login">
    </form>

    <br><br>
    
    <div class="info">
        <strong>Security Features Applied:</strong><br>
        • SQL Injection prevented using Prepared Statements<br>
        • Input is trimmed and properly bound<br>
        • Parameterized queries used
    </div>

    <p><small>
        Note: This is the secure version of the login page.<br>
        The password field is set to visible text for easier demonstration and testing.
    </small></p>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>