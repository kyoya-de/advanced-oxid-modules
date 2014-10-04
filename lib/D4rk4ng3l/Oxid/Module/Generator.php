<?php
namespace D4rk4ng3l\Oxid\Module;

use D4rk4ng3l\Oxid\AdvancedModule\Metadata;
use D4rk4ng3l\Oxid\CodeGenerator\Php\Code;
use D4rk4ng3l\Oxid\CodeGenerator\Php\Method;
use D4rk4ng3l\Oxid\CodeGenerator\Php\PhpClass;
use D4rk4ng3l\Oxid\CodeGenerator\Php\PhpFile;
use D4rk4ng3l\Oxid\CodeGenerator\Php\PhpUse;
use D4rk4ng3l\Oxid\CodeGenerator\Php\Property;
use D4rk4ng3l\Oxid\AdvancedModule\Annotations;

/**
 * Class Generator
 *
 * @package D4rk4ng3l\Oxid\Module
 */
class Generator
{

    /**
     * @var array
     */
    private $classesToLoad = array();

    /**
     * @var array
     */
    private $mappings = array();

    /**
     * @var array
     */
    private $metadata = array();

    /**
     * @var string
     */
    private $oxidPath;

    /**
     * @var string
     */
    private $modulePath;

    /**
     * @param string $oxidPath
     */
    public function __construct($oxidPath, $modulePath)
    {
        $this->oxidPath = $oxidPath;
        $this->modulePath = $modulePath;
    }

    /**
     * @param $mappings
     *
     * @return $this
     */
    public function addMappings($mappings)
    {
        foreach ($mappings as $mapping) {
            $this->addMapping($mapping);
        }

        return $this;
    }

    /**
     * @param Mapping $mapping
     *
     * @return $this
     */
    public function addMapping(Mapping $mapping)
    {
        $oxidClass   = $mapping->getOxidClass();
        $oxidMethod  = $mapping->getOxidMethod();
        $moduleClass = $mapping->getModuleClass();
        $moduleFile  = $mapping->getModuleFile();

        if (!isset($this->mappings[$oxidClass])) {
            $this->mappings[$oxidClass] = array();
        }

        $needNewProxy = true;
        foreach ($this->mappings[$oxidClass] as $proxyClass => $classMethods) {
            if (!isset($classMethods[$oxidMethod])) {
                $this->setMapping($oxidClass, $proxyClass, $oxidMethod, $moduleFile, $moduleClass, $mapping);
                $needNewProxy = false;
                break;
            }
        }

        if ($needNewProxy) {
            $proxyClass = (count($this->mappings[$oxidClass]) + 1);
            $this->setMapping($oxidClass, $proxyClass, $oxidMethod, $moduleFile, $moduleClass, $mapping);
        }

        return $this;
    }

    /**
     * @param $metadataClassName
     */
    public function generate($metadataClassName)
    {
        $oxidPath = $this->oxidPath;
        $this->metadata = array();
        foreach ($this->mappings as $oxidClass => $proxies) {
            foreach ($proxies as $proxyName => $mappings) {
                $phpFile = new PhpFile();
                $phpUse = new PhpUse();
                $moduleId = "aom{$proxyName}";
                $proxyClassName = "{$moduleId}_{$oxidClass}";
                $phpClass = new PhpClass($proxyClassName, $phpUse);
                $phpClass->setExtension("{$proxyClassName}_parent");

                foreach ($this->classesToLoad[$oxidClass][$proxyName] as $moduleClassName => $moduleFile) {
                    $methodName = $this->sanatizeModuleClass($moduleClassName);

                    $instanceProperty = lcfirst($methodName);
                    $property = new Property($instanceProperty);
                    $property->setVisibility(Property::VISIBILITY_PRIVATE);
                    $phpClass->addProperty($property);

                    $method = new Method("get{$methodName}");
                    $method->setVisibility(Method::VISIBILITY_PRIVATE);
                    $body = "if (!class_exists('{$moduleClassName}', false)) {\n";
                    $body .= "include '{$moduleFile}';\n";
                    $body .= "}\n";
                    $body .= "if (null === \$this->{$instanceProperty}) {\n";
                    $body .= "\$this->{$instanceProperty} = new \\{$moduleClassName}();\n";
                    $body .= "}\n";
                    $body .= "return \$this->{$instanceProperty};\n";
                    $method->setBody($body);
                    $phpClass->addMethod($method);
                }

                /** @var Mapping $mapping */
                foreach ($mappings as $mapping) {
                    $body = "\$class = \$this->get" . $this->sanatizeModuleClass($mapping->getModuleClass());
                    $body .= "();\n";
                    $body .= "\$args = func_get_args();\n";
                    $resultVarAssign = "";
                    $resultVarReturn = "";
                    if ($mapping->hasReturn()) {
                        $resultVar = "\$result";
                        $resultVarAssign = "$resultVar = ";
                        $resultVarReturn = "return {$resultVar};\n";
                    }

                    if (Annotations\Method::EXECUTE_BEFORE === $mapping->getParentExecution()) {
                        $body .= "{$resultVarAssign}call_user_func_array(array('parent', __FUNCTION__), \$args);\n";
                    }

                    $body .= "{$resultVarAssign}call_user_func_array(array(\$class, '{$mapping->getModuleMethod()}'), \$args);\n";

                    if (Annotations\Method::EXECUTE_AFTER === $mapping->getParentExecution()) {
                        $body .= "{$resultVarAssign}call_user_func_array(array('parent', __FUNCTION__), \$args);\n";
                    }

                    $body .= $resultVarReturn;

                    $methodReflection = new \ReflectionMethod($mapping->getOxidClass(), $mapping->getOxidMethod());
                    $method = new Method($mapping->getOxidMethod(), array(), $body);
                    foreach ($methodReflection->getParameters() as $parameter) {
                        $method->addParameter(
                            new Method\Parameter(
                                $parameter->getName(),
                                $parameter->getDefaultValue(),
                                !$parameter->isOptional()
                            )
                        );
                    }

                    switch (true) {
                        case $methodReflection->isPublic():
                            $method->setVisibility(Method::VISIBILITY_PUBLIC);
                            break;
                        case $methodReflection->isProtected():
                            $method->setVisibility(Method::VISIBILITY_PROTECTED);
                            break;
                        case $methodReflection->isPrivate():
                            $method->setVisibility(Method::VISIBILITY_PRIVATE);
                            break;
                    }

                    $method->setFinal($methodReflection->isFinal());
                    $method->setAbstract($methodReflection->isAbstract());

                    $phpClass->addMethod($method);
                }

                $vendorPath = $this->modulePath;
                do {
                    $vendorPath = dirname($vendorPath) . '/vendor';
                    if ('/' === $vendorPath || ':' == substr($vendorPath, 1, 1)) {
                        throw new \RuntimeException('Can not locate the vendor directory!');
                    }
                } while (!file_exists($vendorPath));

                $phpAutoloader = new Code("include_once '{$vendorPath}/autoload.php';\n");

                $phpFile->addBlock($phpAutoloader);
                $phpFile->addBlock($phpUse);
                $phpFile->addBlock($phpClass);

                $proxyClassExtend = str_replace('_', '/', $proxyClassName) . "/{$proxyClassName}";
                $proxyClassFile = "{$oxidPath}/modules/{$proxyClassExtend}.php";

                if (!file_exists(dirname($proxyClassFile))) {
                    mkdir(dirname($proxyClassFile), 0777, true);
                }

                file_put_contents($proxyClassFile, $phpFile->generate());

                /** @var Metadata $metadataClass */
                $metadataClass = $this->getMetadata($proxyName, $metadataClassName, $moduleId);
                $metadataClass->getExtensions()->addExtension($oxidClass, $proxyClassExtend);

                file_put_contents("{$oxidPath}/modules/{$moduleId}/metadata.php", $metadataClass->generate());
            }
        }
    }

    /**
     * @param $proxyName
     * @param $metadataClassName
     * @param $moduleId
     *
     * @return mixed
     */
    private function getMetadata($proxyName, $metadataClassName, $moduleId)
    {
        if (!isset($this->metadata[$proxyName])) {
            $this->metadata[$proxyName] = new $metadataClassName($moduleId, true);
        }

        return $this->metadata[$proxyName];
    }

    /**
     * @param         $oxidClass
     * @param         $proxyClass
     * @param         $oxidMethod
     * @param         $moduleFile
     * @param         $moduleClass
     * @param Mapping $mapping
     */
    private function setMapping($oxidClass, $proxyClass, $oxidMethod, $moduleFile, $moduleClass, Mapping $mapping)
    {
        $this->mappings[$oxidClass][$proxyClass][$oxidMethod]       = $mapping;
        $this->classesToLoad[$oxidClass][$proxyClass][$moduleClass] = $moduleFile;
    }

    /**
     * @param $moduleClassName
     *
     * @return mixed|string
     */
    private function sanatizeModuleClass($moduleClassName)
    {
        $methodName = str_replace('\\', ' ', $moduleClassName);
        $methodName = ucwords($methodName);
        $methodName = str_replace(' ', '', $methodName);

        return $methodName;
    }
} 