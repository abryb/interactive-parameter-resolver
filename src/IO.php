<?php

declare(strict_types=1);

namespace Abryb\InteractiveParameterResolver;

use Symfony\Component\Console\Helper\QuestionHelper as SymfonyQuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

/**
 * @author Błażej Rybarkiewicz <b.rybarkiewicz@gmail.com>
 */
class IO
{
    /**
     * @var InputInterface
     */
    private $input;
    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var SymfonyQuestionHelper
     */
    private $symfonyQuestionHelper;

    public function __construct(InputInterface $input, OutputInterface $output)
    {
        $this->input                 = $input;
        $this->output                = $output;
        $this->symfonyQuestionHelper = new SymfonyQuestionHelper();
    }

    public function ask(Question $question)
    {
        return $this->symfonyQuestionHelper->ask($this->input, $this->output, $question);
    }

    public function getInput(): InputInterface
    {
        return $this->input;
    }

    public function getOutput(): OutputInterface
    {
        return $this->output;
    }

    public function getSymfonyQuestionHelper(): SymfonyQuestionHelper
    {
        return $this->symfonyQuestionHelper;
    }
}
