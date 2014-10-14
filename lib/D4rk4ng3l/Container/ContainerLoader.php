<?php

namespace D4rk4ng3l\Container;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\ConfigCache;
use Symfony\Component\DependencyInjection\Dumper\PhpDumper;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use CompiledContainer;

class ContainerLoader
{
    private static $appPath;
    private static $cachePath;
    private static $configPath;

    /**
     * @param string $appPath
     * @param string $env
     *
     * @return CompiledContainer
     */
    public static function getContainer($appPath, $env = null)
    {
        static::$appPath = $appPath;
        static::$cachePath = $appPath . '/app/cache';
        static::$configPath = $appPath . '/app/config';

        $containerCacheFile   = static::$cachePath . '/container.php';
        $containerConfigCache = new ConfigCache($containerCacheFile, $env != 'prod');

        if (!$containerConfigCache->isFresh()) {
            $containerBuilder = static::buildContainer();
            $dumper           = new PhpDumper($containerBuilder);
            $containerConfigCache->write(
                $dumper->dump(array('class' => 'CompiledContainer')),
                $containerBuilder->getResources()
            );
        }

        require_once $containerCacheFile;

        $container = new CompiledContainer();

        return $container;
    }

    /**
     * @return ContainerBuilder
     */
    private static function buildContainer()
    {
        $containerBuilder = new ContainerBuilder();

        $containerBuilder->setParameter('app.path', static::$appPath);

        // 1st Load service parameters
        $yamlFileLoader = new YamlFileLoader($containerBuilder, new FileLocator(static::$configPath));
        $yamlFileLoader->load("parameters.yml");

        // and now the services
        $xmlFileLoader = new XmlFileLoader($containerBuilder, new FileLocator(__DIR__ . '/../../../config'));
        $xmlFileLoader->load("services.xml");

        $containerBuilder->compile();

        return $containerBuilder;
    }
}
