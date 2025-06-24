<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Check if user is admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header("Location: login.html");
    exit();
}

$debug_log = 'registration_debug.log';
$log_content = '';

if (file_exists($debug_log)) {
    $log_content = file_get_contents($debug_log);
} else {
    $log_content = "No debug log file found.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Merriweather:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="styles1.css">
    <title>Registration Debug Log - SEGMA STORE</title>
    <style>
        .debug-container {
            max-width: 1200px;
            margin: 50px auto;
            padding: 30px;
            background: #1a1a1a;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.3);
        }

        .debug-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .debug-title {
            color: #b8d7e0;
            margin: 0;
        }

        .debug-actions {
            display: flex;
            gap: 10px;
        }

        .debug-btn {
            padding: 8px 16px;
            background: #b8d7e0;
            color: #1a1a1a;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            transition: background 0.3s;
        }

        .debug-btn:hover {
            background: #9bc5d1;
        }

        .debug-content {
            background: #2a2a2a;
            padding: 20px;
            border-radius: 4px;
            font-family: monospace;
            white-space: pre-wrap;
            color: #b5bbba;
            max-height: 600px;
            overflow-y: auto;
        }

        .error {
            color: #ff6b6b;
        }

        .info {
            color: #4CAF50;
        }

        .warning {
            color: #ffa500;
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.ico" alt="SEGMA Logo" />
            <span class="logo-text">SEGMA</span>
        </div>
        
        <nav class="main-nav">
            <div class="nav-icons">
                <a href="index.html"><span class="material-icons">home</span></a>
            </div>
        </nav>

        <div class="right-icons">
            <a href="admin_logout.php" class="logout-btn">Logout</a>
        </div>
    </header>

    <main>
        <div class="debug-container">
            <div class="debug-header">
                <h1 class="debug-title">Registration Debug Log</h1>
                <div class="debug-actions">
                    <button class="debug-btn" onclick="clearLog()">Clear Log</button>
                    <button class="debug-btn" onclick="downloadLog()">Download Log</button>
                    <button class="debug-btn" onclick="refreshLog()">Refresh</button>
                </div>
            </div>
            <div class="debug-content" id="debugContent">
                <?php
                // Process and display log content with syntax highlighting
                $lines = explode("\n", $log_content);
                foreach ($lines as $line) {
                    if (strpos($line, '[ERROR]') !== false) {
                        echo '<span class="error">' . htmlspecialchars($line) . '</span><br>';
                    } elseif (strpos($line, '[INFO]') !== false) {
                        echo '<span class="info">' . htmlspecialchars($line) . '</span><br>';
                    } elseif (strpos($line, '[WARNING]') !== false) {
                        echo '<span class="warning">' . htmlspecialchars($line) . '</span><br>';
                    } else {
                        echo htmlspecialchars($line) . '<br>';
                    }
                }
                ?>
            </div>
        </div>
    </main>

    <script>
        function clearLog() {
            if (confirm('Are you sure you want to clear the debug log?')) {
                fetch('clear_debug_log.php')
                    .then(response => response.text())
                    .then(() => {
                        document.getElementById('debugContent').innerHTML = 'Log cleared.';
                    });
            }
        }

        function downloadLog() {
            const content = document.getElementById('debugContent').innerText;
            const blob = new Blob([content], { type: 'text/plain' });
            const url = window.URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'registration_debug.log';
            a.click();
            window.URL.revokeObjectURL(url);
        }

        function refreshLog() {
            location.reload();
        }
    </script>
</body>
</html> 