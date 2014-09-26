<?php
namespace D4rk4ng3l\Oxid\Commands;

use D4rk4ng3l\Oxid\AdvancedModule\Method;
use D4rk4ng3l\Oxid\Compiler\Compiler;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Composer\Autoload\ClassLoader;

class CompileCommand extends Command
{
    protected function configure()
    {
        $this->setName('compile')
            ->setDescription('Generates all files for the real OXID module.')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to your module');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $compiler = new Compiler(new AnnotationReader(), $output);
        $compiler->compile($input->getArgument('path'));
    }
}