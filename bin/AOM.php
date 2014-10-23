<?php
use Doctrine\Common\Annotations\AnnotationRegistry;
use D4rk4ng3l\Container\ContainerLoader;
use D4rk4ng3l\Oxid\Commands\CompileCommand;
use D4rk4ng3l\Console\Application;

class AOM
{
    private static $appPath;

    private static $registered = false;

    public static function getEnv()
    {
        return getenv('AOM_ENV') ?: 'prod';
    }

    /**
     * @return string
     */
    public static function getAppPath()
    {
        if (null === self::$appPath) {
            $paths = array(
                __DIR__ . '/..',
                __DIR__ . '/../../../..',
            );

            foreach ($paths as $path) {
                if (file_exists($path . '/vendor/autoload.php')) {
                    self::$appPath        = realpath($path);
                    break;
                }
            }

            if (!isset(self::$appPath)) {
                throw new \RuntimeException("Can't determine the application path.");
            }
        }

        return self::$appPath;
    }

    /**
     *
     */
    public static function registerAutoloader()
    {
        if (!self::$registered) {
            $classLoader = self::getAutoloader();
            AnnotationRegistry::registerLoader(array($classLoader, 'loadClass'));
        }
    }

    /**
     * @return CompiledContainer
     */
    public static function getContainer()
    {
        return ContainerLoader::getContainer(self::getAppPath());
    }

    /**
     * @throws Exception
     */
    public static function run()
    {
        self::registerAutoloader();

        $app = new Application();
        $app->add(new CompileCommand());
        $app->setContainer(self::getContainer());
        $app->setDefaultCommand('compile');
        $app->run();
    }

    /**
     * @throws \RuntimeException
     *
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getAutoloader()
    {
        $autoLoaderFile = self::getAppPath() . '/vendor/autoload.php';

        if (!file_exists($autoLoaderFile)) {
            throw new \RuntimeException("Can't find autoloader.");
        }

        /** @var \Composer\Autoload\ClassLoader $loader */
        $loader = include $autoLoaderFile;

        return $loader;
    }
}
