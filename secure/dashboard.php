<?php
// secure/dashboard.php - SECURE VERSION
// SQL Injection vulnerability has been fixed using Prepared Statements
session_start();
if (!isset($_SESSION['user'])) {
    // SECURITY: Access control — block unauthenticated users from this page.
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
    <title>Secure Dashboard</title>
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
        th { background-color: #f2f2f2; }
        .info {
            background: #e6ffe6;
            padding: 15px;
            border: 1px solid #99cc99;
            border-radius: 5px;
            margin: 25px 0;
        }
        input[type="text"] { width: 350px; }
    </style>
</head>
<body>
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['user']); ?>! (Secure Dashboard)</h2>
    <!-- SECURITY: Output encoding — keep user-controlled values HTML-escaped when rendering. -->

    <h3>Search Users</h3>
    <form method="GET">
        Search by Username: 
        <input type="text" name="search" placeholder="Enter username">
        <input type="submit" value="Search">
    </form>

    <br><br>
    
    <?php
    if (isset($_GET['search']) && !empty($_GET['search'])) {
        $raw_search = trim((string) $_GET['search']);
        $search = "%" . $raw_search . "%";

        // SECURITY: Prepared statement — this is the key row that prevents SQL Injection.
        $query_template = "SELECT id, username, email FROM users WHERE username LIKE ?";
        echo "<p style='color:#555; font-size:0.95rem;'><strong>Query Preview (Safe):</strong><br>"
            . htmlspecialchars($query_template)
            . "<br><strong>Bound Parameter:</strong> "
            . htmlspecialchars($search)
            . "</p>";

        // SECURITY: Bind parameters — user input must be passed via bind_param(), never string-concatenated into SQL.
        $stmt = $conn->prepare($query_template);
        $stmt->bind_param("s", $search);
        $stmt->execute();
        $result = $stmt->get_result();

        echo "<h4>Search Results:</h4>";
        echo "<table>";
        echo "<tr><th>ID</th><th>Username</th><th>Email</th></tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            // SECURITY: Output encoding — values from DB are treated as untrusted when rendering into HTML.
            echo "<td>" . htmlspecialchars((string) ($row['id'] ?? '')) . "</td>";
            echo "<td>" . htmlspecialchars((string) ($row['username'] ?? '')) . "</td>";
            echo "<td>" . htmlspecialchars((string) ($row['email'] ?? '')) . "</td>";
            echo "</tr>";
        }
        echo "</table>";
        $stmt->close();
    }
    ?>

    <div class="info">
        <strong>Security Features Applied:</strong><br>
        • SQL Injection prevented using Prepared Statements<br>
        • User input is properly escaped with htmlspecialchars()<br>
        • Search uses parameterized query with LIKE
    </div>

    <br>
    <!-- <a href="ping.php">Go to Secure Ping Tool</a><br><br> -->
    <a href="../index.php">← Back to Home</a>
</body>
</html>