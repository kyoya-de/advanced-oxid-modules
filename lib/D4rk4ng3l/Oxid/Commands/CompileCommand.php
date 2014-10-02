<?php
namespace D4rk4ng3l\Oxid\Commands;

use D4rk4ng3l\Oxid\AdvancedModule\Metadata;
use D4rk4ng3l\Oxid\Compiler\Compiler;
use D4rk4ng3l\Oxid\Compiler\TokenParser;
use D4rk4ng3l\Oxid\Module\Generator;
use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

class CompileCommand extends Command
{
    protected function configure()
    {
        $this->setName('compile')
            ->setDescription('Generates all files for the real OXID module.')
            ->addArgument('path', InputArgument::REQUIRED, 'Path to your module')
            ->addArgument('oxid', InputArgument::REQUIRED, 'Path to your OXID installation');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $path     = realpath($input->getArgument('path'));
        $oxidPath = realpath($input->getArgument('oxid'));

        $path     = str_replace('\\', '/', $path);
        $oxidPath = str_replace('\\', '/', $oxidPath);

        $commonPath = $this->findCommonPath($path, $oxidPath);

        chdir($commonPath);

        $path     = str_replace($commonPath, '', $path);
        $oxidPath = str_replace($commonPath, '', $oxidPath);

        include "$oxidPath/bootstrap.php";

        $finder = new Finder();
        $finder->in($path)->name('*.php')->depth(1);

        foreach ($finder as $file) {
            $tokenParser = new TokenParser(file_get_contents($file));
            $tokenParser->parse();
            $className = $tokenParser->getFullClassName();
            if (!class_exists($className, false)) {
                include $file;
            }

            /** @var Metadata $moduleMetadata */
            $compiler = new Compiler(new AnnotationReader(), $output);
            $mappings = $compiler->compile(dirname($file));

            $output->writeln('Generating OXID modules.');
            $generator = new Generator();
            $generator->addMappings($mappings);
            $generator->generate($oxidPath, $className);
        }
    }

    /**
     * @param $path
     * @param $oxidPath
     *
     * @return string
     */
    protected function findCommonPath($path, $oxidPath)
    {
        $pathParts     = explode('/', $path);
        $oxidPathParts = explode('/', $oxidPath);

        $i = 0;
        while (isset($pathParts[$i], $oxidPathParts[$i]) && $pathParts[$i] == $oxidPathParts[$i]) {
            $i++;
        }

        $commonPath = implode('/', array_slice($pathParts, 0, $i)) . '/';

        return $commonPath;
    }
}