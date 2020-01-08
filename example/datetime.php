<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Abryb\InteractiveParameterResolver\InteractiveFunctionInvokerFactory;
use Abryb\InteractiveParameterResolver\IO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$input  = new ArgvInput();
$output = new ConsoleOutput();

$invoker = InteractiveFunctionInvokerFactory::createInvoker(new IO($input, $output));

class Timer
{
    public function __construct(\DateTime $startDate)
    {
    }
}


$invoker->constructObject(Timer::class);