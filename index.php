<?php
// index.php - Main Page (with ?show_all=1 support)
session_start();

$show_all = isset($_GET['show_all']) && $_GET['show_all'] == 1;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web Application Security - Assignment</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            margin: 0;
            padding: 20px;
            min-height: 100vh;
            color: #333;
        }
        .container {
            background: white;
            max-width: 900px;
            margin: 0 auto;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
            overflow: hidden;
        }
        .assignment-box {
            padding: 25px;
            text-align: center;
            background: #f8f9fa;
            border-bottom: 1px solid #ddd;
        }
        .ping-section {
            padding: 25px;
            background: #f8f9fa;
            border-top: 1px solid #ddd;
        }
        .ping-result {
            background: #f4f4f4;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            white-space: pre-wrap;
            font-family: Consolas, monospace;
        }
        .content { padding: 30px 40px; }
        .section {
            padding: 25px;
            margin: 20px 0;
            border-radius: 8px;
        }
        .vulnerable { background: #ffebee; border-left: 6px solid #f44336; }
        .secure     { background: #e8f5e9; border-left: 6px solid #4caf50; }
        .status {
            display: inline-block;
            padding: 10px 25px;
            border-radius: 30px;
            font-weight: bold;
            margin: 15px 0;
        }
        .vuln-status  { background: #ffcdd2; color: #c62828; }
        .secure-status{ background: #c8e6c9; color: #2e7d32; }
        ul { list-style: none; padding: 0; }
        li { margin: 14px 0; font-size: 1.05rem; }
        li a { color: #667eea; text-decoration: none; font-weight: 500; }
        li a:hover { text-decoration: underline; }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">

        <!-- Assignment Header -->
        <div class="assignment-box">
            <h2>Web Application Security - Assignment</h2>
            <p><strong>Name:</strong> Kwong Chi Hin Isaac</p>
            <p><strong>Student ID:</strong> 240296882</p>
            <p><strong>Class:</strong> IT524122/PTE/2 </p>
        </div>

        <!-- Host Check Alive -->
        <div class="ping-section">
            <form method="POST">
                <strong>Host (Check Alive):</strong>
                <input type="text" name="host" placeholder="e.g. 8.8.8.8 or google.com" style="width: 280px;" required>
                
                <strong>No of PING:</strong>
                <select name="count">
                    <option value="1">ONE</option>
                    <option value="2">TWO</option>
                    <option value="3" selected>THREE</option>
                    <option value="4">FOUR</option>
                </select>
                <button type="submit">Submit</button>
            </form>

            <?php
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $host = trim($_POST['host'] ?? '');
                $count = isset($_POST['count']) ? (int) $_POST['count'] : 4;
                if ($count < 1 || $count > 4) {
                    $count = 4;
                }
                if (filter_var($host, FILTER_VALIDATE_IP) || preg_match('/^[a-zA-Z0-9.-]+$/', $host)) {
                    $safe_host = escapeshellarg($host);
                    echo "<div class='ping-result'>";
                    echo "<strong>Ping Result for " . htmlspecialchars($host) . " (No. of PING: " . $count . "):</strong><br><br>";
                    if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
                        system("ping -n " . $count . " " . $safe_host);
                    } else {
                        system("ping -c " . $count . " " . $safe_host);
                    }
                    echo "</div>";
                } else {
                    echo "<p style='color:red; margin-top:10px;'>Invalid hostname or IP address.</p>";
                }
            }
            ?>
        </div>

        <div class="content">
            <h2 style="text-align:center; color:#444;">Vulnerability Demonstration</h2>

            <!-- Vulnerable Version -->
            <div class="status vuln-status">
                🛑 Vulnerable Version – Attack Demonstration (Task B)
            </div>

            <div class="section vulnerable">
                <h3>Vulnerable Version</h3>
                <ul>
                    <li><a href="vulnerable/login.php">→ Login (SQL Injection)</a></li>
                    <li><a href="vulnerable/dashboard.php">→ Dashboard (SQL Injection)</a></li>
                    <li><a href="vulnerable/lfi.php">→ Local File Inclusion (LFI)</a></li>
                    <?php if ($show_all): ?>
                        <li><a href="vulnerable/ping.php">→ Ping Utility (Command Injection)</a></li>
                        <li><a href="vulnerable/xss.php">→ Guestbook (XSS)</a></li>
                    <?php endif; ?>
                </ul>
            </div>

            <!-- Secure Version -->
            <div class="status secure-status">
                ✅ Secure Version – Vulnerabilities Fixed (Task C & D)
            </div>

            <div class="section secure">
                <h3>Secure Version</h3>
                <ul>
                    <li><a href="secure/login.php">→ Secure Login</a></li>
                    <li><a href="secure/dashboard.php">→ Secure Dashboard</a></li>
                    <li><a href="secure/lfi.php">→ Secure Local File Inclusion</a></li>
                    <?php if ($show_all): ?>
                        <li><a href="secure/ping.php">→ Secure Ping Utility</a></li>
                        <li><a href="secure/xss.php">→ Secure Guestbook (XSS Fixed)</a></li>
                    <?php endif; ?>
                </ul>
            </div>

             <!-- Show All Vulnerabilities --> 
            <?php if (!$show_all): ?>
                <p style="text-align:center; margin-top:30px;">
                    <a href="?show_all=1"></a>
                </p>
            <?php endif; ?>
        </div>

        <div class="footer">
            <p>HONG KONG INSTITUTE OF INFORMATION TECHNOLOGY (CHAI WAN)</p>
            <p>Higher Diploma in Cybersecurity — Web Application Security Assignment</p>
        </div>
    </div>
</body>
</html>