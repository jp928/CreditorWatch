<?php declare(strict_types = 1);

namespace App\Container;

/**
 * Interface ContainerInterface
 *
 * @package App\Container
 */
interface ContainerInterface
{

    /**
     * Resolve dependency
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $class
     * @return mixed
     */
    public function resolve(string $class);
}
