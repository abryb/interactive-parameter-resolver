<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Abryb\InteractiveParameterResolver\InteractiveFunctionInvokerFactory;
use Abryb\InteractiveParameterResolver\IO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$invoker = InteractiveFunctionInvokerFactory::createInvoker(new IO(new ArgvInput(), new ConsoleOutput()));

class Tree {
    /**
     * Tree constructor.
     * @param bool $isBig well this is comment, but tree should be big
     * @param int $numberOfBranches
     * @param float $height
     * @param string $description
     */
    public function __construct(bool $isBig, int $numberOfBranches, float $height, string $description)
    {
    }
}


$invoker->constructObject(Tree::class);