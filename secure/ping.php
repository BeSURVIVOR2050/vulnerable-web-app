<?php
// secure/ping.php - SECURE VERSION
// Command Injection Fixed + Windows/Linux Compatible + Better Output Format
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Secure Ping Utility</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        pre { 
            background: #f8f8f8; 
            padding: 15px; 
            border: 1px solid #ccc; 
            border-radius: 5px;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
        .success { color: green; }
        .error { color: red; }
    </style>
</head>
<body>
    <h2>Secure Ping a Host</h2>
    
    <form method="POST">
        Enter IP or Hostname: 
        <input type="text" name="host" size="40" placeholder="e.g. 8.8.8.8 or google.com" required>
        <input type="submit" value="Ping">
    </form>

    <br><br>
    
    <?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $host = trim((string) ($_POST['host'] ?? ''));

        // SECURITY: Strict input validation — this is the key check that blocks command injection payloads.
        if (empty($host)) {
            echo "<p class='error'>Error: Hostname or IP cannot be empty.</p>";
        }
        // SECURITY: Prevent option-injection — disallow starting with '-' so user can't smuggle ping flags.
        elseif (str_starts_with($host, '-')) {
            echo "<p class='error'>Error: Invalid hostname or IP address format.</p>";
        }
        elseif (filter_var($host, FILTER_VALIDATE_IP) || 
                preg_match('/^[a-zA-Z0-9.-]+$/', $host)) {

            // SECURITY: Shell escaping — this is the key row that prevents breaking out of the ping command.
            $safe_host = escapeshellarg($host);

            echo "<h3 class='success'>Ping Result for: " . htmlspecialchars($host) . "</h3>";
            echo "<pre>";

            // Select Correct Ping Parameters Based on Operating System
            if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                // Windows Uses -n 4
                system("ping -n 4 " . $safe_host, $return_var);
            } else {
                // Linux / macOS Uses -c 4
                // SECURITY: Use `--` to stop ping from treating the host as an option.
                system("ping -c 4 -- " . $safe_host, $return_var);
            }

            echo "</pre>";

            if ($return_var !== 0) {
                echo "<p class='error'>Warning: Ping command returned non-zero exit code.</p>";
            }
        } 
        else {
            echo "<p class='error'>Error: Invalid hostname or IP address format.</p>";
        }
    }
    ?>

    <p><small>
        <strong>Security Features Applied:</strong><br>
        • Input validation using filter_var() and regex<br>
        • Command escaping with escapeshellarg()<br>
        • Platform-specific ping parameters (Windows: -n, Linux: -c)
    </small></p>

    <br>
    <a href="../index.php">← Back to Home</a>
</body>
</html>