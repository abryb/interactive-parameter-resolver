<?php

include_once __DIR__ . '/../vendor/autoload.php';

use Abryb\InteractiveParameterResolver\InteractiveFunctionInvokerFactory;
use Abryb\InteractiveParameterResolver\IO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$input  = new ArgvInput();
$output = new ConsoleOutput();

$invoker = InteractiveFunctionInvokerFactory::createInvoker(new IO($input, $output));

class Book {

    /**
     * Book constructor.
     * @param int $id
     * @param string $title
     * @param string[] $chapters
     */
    public function __construct(int $id, string $title, array $chapters)
    {
    }
}

class Author {

    /**
     * Author constructor.
     * @param string $name
     * @param string $lastName a last name
     * @param Book[] $books
     */
    public function __construct(string $name, string $lastName, array $books)
    {
    }
}


$invoker->constructObject(Author::class);