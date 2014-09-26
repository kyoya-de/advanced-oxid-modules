<?php
/**
 * Created by PhpStorm.
 * User: Stefan
 * Date: 15.09.14
 * Time: 00:40
 */

namespace D4rk4ng3l\Oxid\Compiler;

use D4rk4ng3l\Oxid\AdvancedModule\Method;
use D4rk4ng3l\Oxid\Module\Mapping;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;
use D4rk4ng3l\Oxid\Compiler\TokenParser;
use Composer\Autoload\ClassLoader;

class Compiler
{
    /**
     * @var Reader
     */
    private $reader;

    /**
     * @var OutputInterface
     */
    private $output;

    /**
     * @var string
     */
    private $annotationClass = "D4rk4ng3l\\Oxid\\AdvancedModule\\Module";

    public function __construct(Reader $reader, OutputInterface $output = null)
    {
        $this->reader = $reader;
        $this->output = $output;
    }

    public function compile($path)
    {
        $this->output->write('Searching for files...');
        $finder = new Finder();
        $finder->files()->name('*.php')->in($path);
        $count = $finder->count();
        $this->output->writeln(sprintf('%u file(s) found', $count));
        $index = 0;
        foreach ($finder as $file) {
            $index++;
            $this->output->writeln(sprintf('Parsing file [%u/%u]: %s', $index, $count, $file));
            $fileContent = file_get_contents($file);
            $tokenParser = new TokenParser($fileContent);
            $tokenParser->parse();
            if (!$tokenParser->hasClass()) {
                $this->output->writeln('skipped!');
                continue;
            }

            include $file;

            $this->processClass($tokenParser->getFullClassName());
        }
    }

    /**
     * @param string $className
     */
    public function processClass($className)
    {
        $reflection = new \ReflectionClass($className);
        if (null === $this->reader->getClassAnnotation($reflection, $this->annotationClass)) {
            return;
        }

        $this->output->writeln("Found class: $className");
        foreach ($reflection->getMethods() as $method) {
            /** @var Method[] $annotations */
            $annotations = $this->reader->getMethodAnnotations($method);
            if (0 == count($annotations)) {
                continue;
            }

            $this->output->writeln(
                sprintf(
                    "Found annotations for method %s::%s.",
                    $method->class,
                    $method->getName()
                )
            );

            foreach ($annotations as $annotation) {
                if (!($annotation instanceof Method)) {
                    continue;
                }

                $this->output->writeln(
                    sprintf(
                        "Found mapping: %s::%s --> %s::%s",
                        $method->class,
                        $method->getName(),
                        $annotation->getClass(),
                        $annotation->getMethod()
                    )
                );

                $mapping = new Mapping();
                $mapping
                    ->setOxidClass($annotation->getClass())
                    ->setOxidMethod($annotation->getMethod())
                    ->setModuleClass($className)
                    ->setModuleMethod($method->getName())
                    ->setReturn($annotation->hasReturnValue())
                    ->setReturns($annotation->getReturns())
                    ->setParentExecution($annotation->getParentExecution())
                    ->setModuleFile($reflection->getFileName());
            }
        }
    }
}