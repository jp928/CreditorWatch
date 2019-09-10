<?php declare(strict_types = 1);

interface TestInterface
{

}

class Test implements TestInterface
{

    public function test(): void
    {
    }

}

$test = new test;
