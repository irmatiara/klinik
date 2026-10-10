<?php

// Create necessary writable directories in /tmp for Vercel Serverless
$directories = [
    '/tmp/storage/framework/views',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/bootstrap/cache',
    '/tmp/storage/logs',
];

foreach ($directories as $dir) {
    if (!file_exists($dir)) {
        @mkdir($dir, 0777, true);
    }
}

// Ensure HTTPS scheme is recognized behind Vercel reverse proxy
if ((isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') || getenv('VERCEL')) {
    $_SERVER['HTTPS'] = 'on';
    $_SERVER['SERVER_PORT'] = 443;
}

// Redirect Laravel storage & compiled view & bootstrap cache paths to writable /tmp
putenv('VIEW_COMPILED_PATH=/tmp/storage/framework/views');
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/storage/framework/views';

putenv('APP_STORAGE=/tmp/storage');
$_ENV['APP_STORAGE'] = '/tmp/storage';

putenv('APP_CONFIG_CACHE=/tmp/storage/bootstrap/cache/config.php');
$_ENV['APP_CONFIG_CACHE'] = '/tmp/storage/bootstrap/cache/config.php';

putenv('APP_SERVICES_CACHE=/tmp/storage/bootstrap/cache/services.php');
$_ENV['APP_SERVICES_CACHE'] = '/tmp/storage/bootstrap/cache/services.php';

putenv('APP_PACKAGES_CACHE=/tmp/storage/bootstrap/cache/packages.php');
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/storage/bootstrap/cache/packages.php';

putenv('APP_ROUTES_CACHE=/tmp/storage/bootstrap/cache/routes-v7.php');
$_ENV['APP_ROUTES_CACHE'] = '/tmp/storage/bootstrap/cache/routes-v7.php';

putenv('APP_EVENTS_CACHE=/tmp/storage/bootstrap/cache/events.php');
$_ENV['APP_EVENTS_CACHE'] = '/tmp/storage/bootstrap/cache/events.php';

// Require Laravel entrypoint
try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    error_log('Vercel Serverless Exception: ' . $e->getMessage() . "\n" . $e->getTraceAsString());
    http_response_code(500);
    echo "<h1>500 Internal Server Error</h1>";
    if (getenv('APP_DEBUG') === 'true' || isset($_ENV['APP_DEBUG']) && $_ENV['APP_DEBUG'] == 'true') {
        echo "<pre style='color:red; background:#f8f9fa; padding:15px; border-radius:5px; overflow:auto;'>";
        echo htmlspecialchars((string)$e);
        echo "</pre>";
    } else {
        echo "<p>Detailed error logged to Vercel logs. Set APP_DEBUG=true in Vercel Environment Variables to view trace on screen.</p>";
    }
}

