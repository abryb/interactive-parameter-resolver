#### Installation

```shell script
composer require abryb/interactive-parameter-resolver
```

#### Usage

##### Function invoking / Object construction
```php
<?php

use Abryb\InteractiveParameterResolver\InteractiveFunctionInvokerFactory;
use Abryb\InteractiveParameterResolver\IO;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

$input  = new ArgvInput();
$output = new ConsoleOutput();

$invoker = InteractiveFunctionInvokerFactory::createInvoker(new IO($input, $output));

$invoker->constructObject(\MyApp\MyCustomObject::class);

```

##### Custom handler

1. Create class

```php
<?php

namespace MyApp;

use Abryb\InteractiveParameterResolver\Parameter;
use Symfony\Component\Console\Style\StyleInterface;

class MyCustomHandler implements \Abryb\InteractiveParameterResolver\ParameterHandlerInterface
{
    public function canHandle(Parameter $parameter): bool
    {
        
    }

    public function handle(Parameter $parameter, StyleInterface $io)
    {
        
    }   
}
```

2. Pass additional handler to factory

```php

use Abryb\InteractiveParameterResolver\InteractiveFunctionInvokerFactory;
$invoker = InteractiveFunctionInvokerFactory::createInvoker(new IO($input, $output), [
    new MyApp\MyCustomHandler(),
]);

```

