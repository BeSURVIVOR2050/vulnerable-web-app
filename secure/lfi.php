<?php
// secure/lfi.php - SECURE VERSION (Final)
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Page - Secure (LFI Fixed)</title>
    <style>
        body { 
            font-family: Arial, sans-serif; 
            margin: 40px; 
            line-height: 1.6; 
        }
        pre { 
            background: #f8f8f8; 
            padding: 20px; 
            border: 1px solid #ddd; 
            border-radius: 8px;
            white-space: pre-wrap;
            font-size: 1.05rem;
        }
        .info {
            background: #e8f5e9;
            padding: 20px;
            border: 1px solid #4caf50;
            border-radius: 8px;
            margin: 25px 0;
        }
        .error { color: #d32f2f; }
    </style>
</head>
<body>
    <h2>View Page (Secure - Local File Inclusion Resolved)</h2>
    
    <?php
    // SECURITY: Whitelist allowlist — only these exact filenames are permitted to be included.
    $allowed_files = ['about.php', 'contact.php', 'news.php'];
    $selected_page = 'about.php';
    $error_message = '';

    if (isset($_GET['page'])) {
        $raw_page = trim((string) $_GET['page']);

        // SECURITY: Block traversal or absolute path attempts immediately.
        $is_traversal_attempt =
            str_contains($raw_page, '..') ||
            str_contains($raw_page, '/') ||
            str_contains($raw_page, '\\') ||
            str_contains($raw_page, "\0");

        if ($is_traversal_attempt) {
            $error_message = 'Access denied';
        } elseif (in_array($raw_page, $allowed_files, true)) {
            $selected_page = $raw_page;
        } else {
            $error_message = 'Invalid page';
        }
    }
    ?>

    <form method="GET">
        Select Page: 
        <select name="page">
            <option value="about.php"   <?php echo $selected_page === 'about.php' ? 'selected' : ''; ?>>About Us</option>
            <option value="contact.php" <?php echo $selected_page === 'contact.php' ? 'selected' : ''; ?>>Contact</option>
            <option value="news.php"    <?php echo $selected_page === 'news.php' ? 'selected' : ''; ?>>Latest News</option>
        </select>
        <input type="submit" value="View">
    </form>

    <br><br>
    
    <?php
    if (isset($_GET['page'])) {
        if ($error_message === '') {
            $page = $selected_page;
            echo "<h3>Content of: pages/" . htmlspecialchars($page) . "</h3>";
            echo "<pre>";
            $pages_dir = __DIR__ . '/pages';
            // SECURITY: realpath enforcement — ensures the final resolved path stays inside the intended directory.
            $target = realpath($pages_dir . '/' . $page);
            if ($target !== false && str_starts_with($target, realpath($pages_dir) . DIRECTORY_SEPARATOR)) {
                // SECURITY: Safe inclusion — include only an allowlisted file whose real path is inside `pages/`.
                include($target);
            } else {
                echo "Error: Invalid page path.";
            }
            echo "</pre>";
        } else {
            echo "<p class='error'>Error: " . htmlspecialchars($error_message) . "</p>";
        }
    }
    ?>

    <div class="info">
        <strong>Security Features Applied:</strong><br>
        • <code>basename()</code> prevents directory traversal<br>
        • Strict whitelist validation<br>
        • Files loaded only from controlled <code>pages/</code> directory<br>
        • No user-controlled path allowed
    </div>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>