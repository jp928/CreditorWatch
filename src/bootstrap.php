<?php declare (strict_types = 1);

use App\Base\App;
use App\Container\Container;

require_once __DIR__ . '/../vendor/autoload.php';

$definitions = require_once __DIR__ . '/definitions.php';

$container = Container::getInstance()->setDefinition($definitions);

return new App($container);
