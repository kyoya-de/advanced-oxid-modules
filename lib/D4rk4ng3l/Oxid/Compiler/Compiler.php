<?php

namespace D4rk4ng3l\Oxid\Compiler;

use D4rk4ng3l\Oxid\AdvancedModule\Annotations\Method;
use D4rk4ng3l\Oxid\Module\Mapping;
use Doctrine\Common\Annotations\Reader;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Finder\Finder;

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
    private $annotationClass = "D4rk4ng3l\\Oxid\\AdvancedModule\\Annotations\\Module";

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

        $mappings = array();
        foreach ($finder as $file) {
            $index++;
            $this->output->writeln(sprintf('Parsing file [%u/%u]: %s', $index, $count, $file));
            $fileContent = file_get_contents($file);
            $tokenParser = new TokenParser($fileContent);
            $tokenParser->parse($fileContent);
            if (!$tokenParser->hasClass()) {
                $this->output->writeln('skipped!');
                continue;
            }

            if (!class_exists($tokenParser->getFullClassName(), false)) {
                include $file;
            }

            $mappings = array_merge(
                $mappings,
                $this->processClass($tokenParser->getFullClassName(), $path)
            );
        }

        return $mappings;
    }

    /**
     * @param string $className
     *
     * @return array
     */
    public function processClass($className, $path)
    {
        $reflection = new \ReflectionClass($className);
        if (null === $this->reader->getClassAnnotation($reflection, $this->annotationClass)) {
            return array();
        }

        $mappings = array();

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
                $moduleFile = $reflection->getFileName();
                $moduleFile = substr($moduleFile, strpos($moduleFile, $path));
                $mapping
                    ->setOxidClass($annotation->getClass())
                    ->setOxidMethod($annotation->getMethod())
                    ->setModuleClass($className)
                    ->setModuleMethod($method->getName())
                    ->setReturn($annotation->hasReturnValue())
                    ->setParentExecution($annotation->getParentExecution())
                    ->setModuleFile($moduleFile);
                $mappings[] = $mapping;
            }
        }

        return $mappings;
    }
}