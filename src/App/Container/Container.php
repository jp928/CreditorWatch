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
    public function resolve(string $class)
    {
        $reflectionClass = new ReflectionClass($class);

        if ($reflectionClass->isInterface()) {
            $class = $this->lookUp($class);
            $reflectionClass = new ReflectionClass($class);
        }

        // do not resolve abstract class yet
        if ($reflectionClass->isAbstract()) {
            return;
        }

        // Fetch the constructor
        $constructor = $reflectionClass->getConstructor();

        // If there is no constructor, instantiate the class
        if (! $constructor) {
            return new $class();
        }

        // Fetch the arguments from the constructor
        $params = $constructor->getParameters();

        // If there is a constructor, but no dependencies intiatiate
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

            // recusively resolve dependencies
            $newInstanceParams[] = $this->resolve(
                $param->getClass()->getName()
            );
        }

        return $reflectionClass->newInstanceArgs($newInstanceParams);
    }
}
