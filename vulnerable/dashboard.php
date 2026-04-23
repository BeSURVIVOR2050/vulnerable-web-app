<?php
// vulnerable/dashboard.php - VULNERABLE VERSION
// Intentionally vulnerable to SQL Injection for Task B demonstration
session_start();
if (!isset($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

require_once __DIR__ . '/../includes/db_connect.php';
$conn = db_connect();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Vulnerable</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6; 
        }
        table { 
            border-collapse: collapse; 
            width: 90%; 
            margin-top: 20px; 
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 10px; 
            text-align: left; 
        }
        th { 
            background-color: #f2f2f2; 
        }
        .warning {
            color: #d32f2f;
            background: #ffe6e6;
            padding: 20px;
            border: 1px solid #ff9999;
            border-radius: 8px;
            margin: 25px 0;
        }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>! (Vulnerable Dashboard)</h2>

    <h3>Search Users</h3>
    <form method="GET">
        Search by Username: 
        <input type="text" name="search" placeholder="Enter username or payload" style="width:350px;">
        <input type="submit" value="Search">
    </form>

    <?php
    if (isset($_GET['search']) && $_GET['search'] !== '') {
        // Keep this page intentionally vulnerable, but make the demo resilient across MySQL versions:
        // In MySQL, `--` comments require whitespace after `--` or they won't start a comment.
        $normalize_mysql_dash_comment = static function (string $s): string {
            return preg_replace('/--(?!\s)/', '-- ', $s) ?? $s;
        };

        $search = $normalize_mysql_dash_comment((string) $_GET['search']);

        // VULNERABLE: Direct SQL concatenation - SQL Injection possible
        // Include password in SELECT so UNION payloads with 4 columns work and passwords can be displayed.
        $sql = "SELECT id, username, email, password FROM users WHERE username LIKE '%$search%'";

        echo "<p style='color:#555; font-size:0.95rem;'><strong>Executed Query:</strong><br>" 
             . htmlspecialchars($sql) . "</p>";

        try {
            $result = mysqli_query($conn, $sql);
        } catch (mysqli_sql_exception $e) {
            $result = false;
            $sql_error = $e->getMessage();
        }

        if ($result) {
            echo "<h4>Search Results:</h4>";
            echo "<table>";
            echo "<tr><th>ID</th><th>Username</th><th>Email</th><th>Password</th></tr>";

            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>" . htmlspecialchars((string) ($row['id'] ?? '')) . "</td>";
                echo "<td>" . htmlspecialchars((string) ($row['username'] ?? '')) . "</td>";
                echo "<td>" . htmlspecialchars((string) ($row['email'] ?? '')) . "</td>";
                echo "<td>" . htmlspecialchars((string) ($row['password'] ?? 'N/A')) . "</td>";   // Show password if available
                echo "</tr>";
            }
            echo "</table>";
        } else {
            $msg = isset($sql_error) ? $sql_error : mysqli_error($conn);
            echo "<p style='color:red;'>SQL Error: " . htmlspecialchars($msg) . "</p>";
        }
    }
    ?>

    <div class="warning">
        ⚠️ <strong>This dashboard is intentionally vulnerable to SQL Injection!</strong><br><br>
        
        <strong>Recommended Payloads for Task B:</strong><br>
        • <code>' OR '1'='1</code> — Show all users<br>
        • <code>' OR '1'='1 -- </code> — Show all users (with comment)<br>
        • <code>' UNION SELECT null, username, email, password FROM users -- </code> — Reveal passwords from the table<br>
        &nbsp;&nbsp;&nbsp;&nbsp;<small>(Note: <code>UNION SELECT</code> must match the main query’s 4 columns: <code>id, username, email, password</code>.)</small><br>
        • <code>admin' -- </code> — Login bypass example (if used in login)
    </div>

    <br>
    <!-- <a href="ping.php">Go to Vulnerable Ping Tool</a><br><br> -->
    <a href="../index.php">← Back to Home</a>
</body>
</html>