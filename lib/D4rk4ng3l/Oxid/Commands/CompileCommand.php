<?php
namespace D4rk4ng3l\Oxid\Commands;

use D4rk4ng3l\Oxid\AdvancedModule\Metadata;
use D4rk4ng3l\Oxid\Compiler\TokenParser;
use D4rk4ng3l\Console\Command;
use D4rk4ng3l\Oxid\Module\Generator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CompileCommand extends Command
{
    protected function configure()
    {
        $this->setName('compile')
            ->setDescription('Generates all files for the real OXID module.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path = $this->container->getParameter('path.module');
        $oxidPath = $this->container->getParameter('path.oxid');

        include "$oxidPath/bootstrap.php";

        $finder = new Finder();
        $finder->in($path)->name('*.php')->depth(1);

        /** @var TokenParser $tokenParser */
        $tokenParser = $this->container->get('compiler.token_parser');
        foreach ($finder as $file) {
            $tokenParser->parse(file_get_contents($file));
            $className = $tokenParser->getFullClassName();
            if (!class_exists($className, false)) {
                include $file;
            }

            /** @var Metadata $moduleMetadata */
            $compiler = $this->container->get('compiler.module');
            $mappings = $compiler->compile(dirname($file));

            $output->writeln('Generating OXID modules.');

            /** @var Generator $generator */
            $generator = $this->container->get('compiler.generator');
            $generator->addMappings($mappings);
            $generator->generate($className);
        }
    }
}