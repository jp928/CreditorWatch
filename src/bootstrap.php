<?php declare (strict_types = 1);

use App\Base\App;

require_once __DIR__ . '/../vendor/autoload.php';

// $settings = require_once __DIR__ . '/settings.php';


$definitions = require_once __DIR__ . '/definitions.php';

// $redis = new Redis();

// $redis->connect('redis', 6379);

return new App($definitions);
