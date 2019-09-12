<?php declare(strict_types = 1);

if (PHP_SAPI === 'cli-server') {
    // To help the built-in PHP dev server, check if the request was actually for
    // something which should probably be served as a static file
    $url  = parse_url($_SERVER['REQUEST_URI']);
    $file = __DIR__ . $url['path'];

    if (is_file($file)) {
        return false;
    }
}

try {
    // bootstrap
    $app = require __DIR__ . '/../src/bootstrap.php';

    // Process logic
    $app->process();
} catch (\Exception $e) {
    die('Error occur: ' . $e->getMessage());
}
