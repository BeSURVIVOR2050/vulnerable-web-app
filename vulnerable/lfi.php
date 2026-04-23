<?php
// vulnerable/lfi.php - VULNERABLE VERSION 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Page - Vulnerable (LFI)</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6; 
        }
        pre { 
            background: #f4f4f4; 
            padding: 15px; 
            border: 1px solid #ddd; 
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .warning {
            color: #d32f2f;
            background: #ffe6e6;
            padding: 20px;
            border: 1px solid #ff9999;
            border-radius: 8px;
            margin: 25px 0;
        }
        .success { color: green; font-weight: bold; }
    </style>
</head>
<body>
    <h2>View Page (Vulnerable - Local File Inclusion)</h2>
    
    <?php
    $selected_page = isset($_GET['page']) ? (string) $_GET['page'] : 'pages/about.php';
    ?>

    <form method="GET">
        Select Page: 
        <select name="page">
            <option value="pages/about.php"   <?php echo $selected_page === 'pages/about.php' ? 'selected' : ''; ?>>About Us</option>
            <option value="pages/contact.php" <?php echo $selected_page === 'pages/contact.php' ? 'selected' : ''; ?>>Contact</option>
            <option value="pages/news.php"    <?php echo $selected_page === 'pages/news.php' ? 'selected' : ''; ?>>Latest News</option>
        </select>
        <input type="submit" value="View">
    </form>

    <br><br>
    
    <?php
    if (isset($_GET['page'])) {
        $page = $selected_page;   // Vulnerable: No protection

        echo "<h3>Content of: " . htmlspecialchars($page) . "</h3>";
        echo "<pre>";

        // Intentionally vulnerable LFI: read arbitrary paths supplied by user.
        // Use file_get_contents so PHP files are shown as source (not executed),
        // which makes the LFI demo clearer in both XAMPP and Docker.
        $content = @file_get_contents($page);
        if ($content === false) {
            echo "[Unable to read file]\n";
            if (@is_file($page)) {
                echo "The file exists but could not be read (permissions?).\n";
            } else {
                echo "The file was not found at that path.\n";
            }
        } else {
            echo htmlspecialchars($content);
        }

        echo "</pre>";
    }
    ?>

    <div class="warning">
        ⚠️ <strong>This page is intentionally vulnerable to Local File Inclusion (LFI)!</strong><br><br>
        
        <strong>Working Attack Examples (Docker / Linux container):</strong><br>
        • Read app source (absolute): <code>?page=/var/www/html/index.php</code><br>
        • Read this page: <code>?page=/var/www/html/vulnerable/lfi.php</code><br>
        • Read Linux hosts: <code>?page=/etc/hosts</code><br>
        • Read passwd: <code>?page=/etc/passwd</code><br>
        • Read environment (may be restricted): <code>?page=/proc/self/environ</code>
    </div>



    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>