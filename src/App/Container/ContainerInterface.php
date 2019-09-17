<?php declare(strict_types = 1);

namespace App\Container;

/**
 * Interface ContainerInterface
 *
 * @package App\Container
 */
interface ContainerInterface
{

    public function resolve(string $class): void;
    /**
     * Wire definition
     *
     * @return void
     */
    // public function wire(array $definition): void;
}
