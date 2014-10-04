<?php

namespace D4rk4ng3l\Console;

use Symfony\Component\Console\Application as SFApp;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class Application extends SFApp
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @return ContainerInterface
     */
    public function getContainer()
    {
        return $this->container;
    }

    /**
     * @param ContainerInterface $container
     */
    public function setContainer($container)
    {
        $this->container = $container;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function configureIO(InputInterface $input, OutputInterface $output)
    {
        parent::configureIO($input, $output);
        if (null !== $this->container) {
            $this->container->set('console.input', $input);
            $this->container->set('console.output', $output);
        }
    }

    /**
     * @param string $name
     *
     * @return \Symfony\Component\Console\Command\Command
     */
    public function find($name)
    {
        $command = parent::find($name);

        if ($command instanceof ContainerAwareInterface) {
            $command->setContainer($this->container);
        }

        return $command;
    }
}