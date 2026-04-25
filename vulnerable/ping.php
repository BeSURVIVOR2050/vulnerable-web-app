<?php
// vulnerable/ping.php - VULNERABLE VERSION
// Intentionally vulnerable to Command Injection for Task B demonstration
// Fixed Windows ping -c issue and improved display quality
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ping Utility - Vulnerable</title>
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
            font-weight: bold; 
            background: #ffe6e6; 
            padding: 15px; 
            border: 1px solid #ff9999;
            border-radius: 5px;
        }
        h3 { color: #333; }
    </style>
</head>
<body>
    <h2>Ping a Host (Vulnerable Version)</h2>
    
    <form method="POST">
        Enter IP or Hostname: 
        <input type="text" name="host" size="40" placeholder="e.g. 8.8.8.8 or google.com" required>
        <input type="submit" value="Ping">
    </form>

    <br><br>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $host = (string) ($_POST['host'] ?? '');   // Intentionally no filtering or escaping → Command Injection vulnerability kept

        echo "<h3>Ping Result for: " . htmlspecialchars($host) . "</h3>";
        echo "<p style='color:#666; font-size:0.9rem;'><strong>Executed Command:</strong></p>";

        // Automatically choose correct ping parameter based on operating system
        if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
            // Windows: run via PowerShell so `;` chaining works (intentionally vulnerable)
            $cmd = "powershell -NoProfile -Command ping -n 4 " . $host . " 2>&1";
        } else {
            // Linux / macOS uses -c 4 (intentionally vulnerable)
            $cmd = "ping -c 4 " . $host . " 2>&1";
        }

        echo "<pre>" . htmlspecialchars($cmd) . "\n\n";
        system($cmd); // ← Intentionally not using escapeshellarg() to keep the vulnerability
        echo "</pre>";
    }
    ?>

    <div class="warning">
        ⚠️ <strong>This page is intentionally vulnerable to Command Injection!</strong><br><br>
        This version does <strong>NOT</strong> perform any input validation or escaping.<br>
        It is designed for educational demonstration of the vulnerability in Task B.
    </div>

    <h4>Command Injection Attack Examples (for Task B demonstration):</h4>
    <ul>
        <?php if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN'): ?>
            <li><code>8.8.8.8 &amp; dir</code> — Display current directory files</li>
            <li><code>8.8.8.8 &amp; whoami</code> — Show current username</li>
            <li><code>8.8.8.8 &amp; net user</code> — List all user accounts</li>
            <li><code>8.8.8.8 &amp; ipconfig</code> — Display network configuration</li>
        <?php else: ?>
            <li><code>8.8.8.8 ; ls</code> — List current directory files</li>
            <li><code>8.8.8.8 ; whoami</code> — Show current username</li>
            <li><code>8.8.8.8 ; id</code> — Show user/group IDs</li>
            <li><code>8.8.8.8 ; uname -a</code> — Display OS information</li>
        <?php endif; ?>
    </ul>

    <p><small>
        Note: This version deliberately retains a serious Command Injection vulnerability.<br>
        It is intended only for Task B attack demonstration.<br>
        This code should never be used in a production environment.
    </small></p>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>