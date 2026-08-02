<?php
$assetPath = 'frontend/assets';
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>404 - Page Not Found | ECMS</title>
    <link rel="stylesheet" href="<?php echo htmlspecialchars($assetPath, ENT_QUOTES); ?>/css/app.css">
    <style>
        .error-page {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 100vh;
            text-align: center;
            padding: 2rem;
        }
        .error-code {
            font-size: 6rem;
            font-weight: 700;
            color: #e74c3c;
            margin-bottom: 1rem;
        }
        .error-title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .error-message {
            color: #666;
            margin-bottom: 2rem;
        }
        .error-link {
            display: inline-block;
            padding: 0.75rem 1.5rem;
            background: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background 0.3s;
        }
        .error-link:hover {
            background: #2980b9;
        }
    </style>
</head>
<body>
    <div class="error-page">
        <div class="error-code">404</div>
        <h1 class="error-title">Page Not Found</h1>
        <p class="error-message">The page you are looking for does not exist or has been moved.</p>
        <a href="<?php echo htmlspecialchars(url('/'), ENT_QUOTES); ?>" class="error-link">Go to Homepage</a>
    </div>
</body>
</html>
