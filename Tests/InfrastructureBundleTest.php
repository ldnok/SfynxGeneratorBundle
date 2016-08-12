<?php
/**
 * Sfynx Api Generator Domain generation unit test
 * @author "Nicolas Blaudez <nblaudez@gmail.com>"
 */
namespace Sfynx\DddGeneratorBundle\Tests;

use \Phake;
use Sfynx\DddGeneratorBundle\Command\GenerateDddApiCommand;
use Sfynx\DddGeneratorBundle\Generator\Api\DddApiGenerator;
use Symfony\Component\Yaml\Parser;

class PresentationTest extends \PHPUnit_Framework_TestCase
{

    protected $entities;
    protected $routes;
    protected $valueObjects;
    protected $environment;

    public function setup()
    {
        $this->assertNotNull($_ENV["swaggerFile"]);
        $this->assertNotNull($_ENV["contextName"]);

        $this->environment = $_ENV["contextName"];
        $this->swaggerFile = $_ENV["swaggerFile"];

        $ymlParser = new Parser();
        $this->dddApiCommand = new GenerateDddApiCommand();
        $this->dddApiCommand->setConfig($ymlParser->parse(file_get_contents($this->swaggerFile)));

        $this->entities = $this->dddApiCommand->parseEntities();
        $this->valueObjects = $this->dddApiCommand->parseValueObjects();
        $this->routes = $this->dddApiCommand->parseRoutes();
    }

    public function checkNamespace($namespace, $file)
    {
        $content = file_get_contents($file);

        if (preg_match('#\s*namespace\s*' . addslashes($namespace) . '\s*;#', $content)) {
            return true;
        } else {
            return false;
        }
    }

    public function checkClassName($className, $file)
    {
        $content = file_get_contents($file);

        if (preg_match('#\s*([Cc]lass|[Ii]nterface)\s*' . $className . '\s*#', $content)) {
            return true;
        } else {
            return false;
        }
    }

    public function testfilesExist()
    {
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Compiler");

        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Compiler/CreateRepositoryFactoryPass.php");
        $this->assertTrue($this->checkNamespace($this->environment . "\\InfrastructureBundle\\DependencyInjection\\Compiler",__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Compiler/CreateRepositoryFactoryPass.php"));
        $this->assertTrue($this->checkClassName("CreateRepositoryFactoryPass", __DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Compiler/CreateRepositoryFactoryPass.php"));

        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Configuration.php");
        $this->assertTrue($this->checkNamespace($this->environment . "\\InfrastructureBundle\\DependencyInjection",__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Configuration.php"));
        $this->assertTrue($this->checkClassName("Configuration", __DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/Configuration.php"));

        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/".$this->environment."InfrastructureBundleExtension.php");
        $this->assertTrue($this->checkNamespace($this->environment . "\\InfrastructureBundle\\DependencyInjection", __DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/".$this->environment."InfrastructureBundleExtension.php"));
        $this->assertTrue($this->checkClassName($this->environment."InfrastructureBundleExtension" , __DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/DependencyInjection/".$this->environment."InfrastructureBundleExtension.php"));

        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/".$this->environment."InfrastructureBundle.php");
        $this->assertTrue($this->checkNamespace($this->environment . "\\InfrastructureBundle",__DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/".$this->environment."InfrastructureBundle.php"));
        $this->assertTrue($this->checkClassName($this->environment ."InfrastructureBundle", __DIR__ . "/../../../../src/" . $this->environment . "/InfrastructureBundle/".$this->environment."InfrastructureBundle.php"));




    }


}