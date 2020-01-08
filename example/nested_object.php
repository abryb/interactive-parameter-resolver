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
     * @param Author $author an author
     * @param string $title
     */
    public function __construct(int $id, Author $author, string $title)
    {
    }
}

class Author {

    /**
     * Author constructor.
     * @param string $name
     * @param string $lastName a last name
     * @param DateTime $birthday
     */
    public function __construct(string $name = 'John', string $lastName = 'Bastvill', \DateTime $birthday)
    {
    }
}


$invoker->constructObject(Book::class);