<?php declare(strict_types = 1);

namespace App\Container;

use App\Exceptions\DependencyNotFoundException;
use ReflectionClass;

class Container implements ContainerInterface
{

    /** @var self $instance */
    private static $instance;

    /** @var string[] $definition */
    private $definition = [];

    /**
     * Get container instance in singleton manner
     *
     * @return self
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self:: $instance = new self();

            return  self:: $instance;
        }

        return self::$instance;
    }

    /**
     * Setter for depedency definition
     *
     * @param string[] $definition
     * @return self
     */
    public function setDefinition(array $definition): self
    {
        $this->definition = $definition;

        return $this;
    }

    /**
     * Lookup the definition dictionary to find the concrete class
     *
     * @param string $interface
     * @return string Resolved concrete class
     */
    protected function lookUp(string $interface): string
    {
        var_dump($this->definition);

        if (array_key_exists($interface, $this->definition)) {
            return $this->definition[$interface];
        }

        throw new DependencyNotFoundException($interface);
    }

    /**
     * Resolve dependency
     * phpcs:disable SlevomatCodingStandard.TypeHints.DisallowMixedTypeHint.DisallowedMixedTypeHint
     * @param string $class
     * @return mixed
     */
    public function resolve(string $class): mixed
    {
        // Reflect on the $class
        $reflectionClass = new ReflectionClass($class);

        if ($reflectionClass->isInterface()) {
            $class = $this->lookUp($class);
            $reflectionClass = new ReflectionClass($class);
          // var_dump($reflectionClass);die();
        }

        // Fetch the constructor (instance of ReflectionMethod)
        $constructor = $reflectionClass->getConstructor();

        // If there is no constructor, there is no
        // dependencies, which means that our job is done.
        if (! $constructor) {
            return new $class();
        }

        // Fetch the arguments from the constructor
        // (collection of ReflectionParameter instances)
        $params = $constructor->getParameters();

        // If there is a constructor, but no dependencies,
        // our job is done.
        if (count($params) === 0) {
            return new $class();
        }

        // This is were we store the dependencies
        $newInstanceParams = [];

        // Loop over the constructor arguments
        foreach ($params as $param) {
            // For now, we just check to see if the argument is
            // a class, so we can instantiate it,
            // otherwise we just pass null.
            if (is_null($param->getClass())) {
                $newInstanceParams[] = null;

                continue;
            }

            // var_dump($param->getClass()->getName());die();
            // This is where 'the magic happens'. We resolve each
            // of the dependencies, by recursively calling the
            // resolve() method.
            // At one point, we will reach the bottom of the
            // nested dependencies we need in order to instantiate
            // the class.
            $newInstanceParams[] = $this->resolve(
                $param->getClass()->getName()
            );
        }

        // Return the reflected class, instantiated with all its
        // dependencies (this happens once for all the
        // nested dependencies).
        return $reflectionClass->newInstanceArgs(
            $newInstanceParams,
        );
    }
}
