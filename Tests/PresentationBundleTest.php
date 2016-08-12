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

class PresentationBundleTest extends \PHPUnit_Framework_TestCase
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


    public function testfilesExist()
    {
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle/DependencyInjection");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle/DependencyInjection/Compiler");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle/DependencyInjection/Compiler/ResettingListenersPass.php");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle/DependencyInjection/Configuration.php");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle/DependencyInjection/".$this->environment."PresentationBundleExtension.php");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/PresentationBundle/".$this->environment."PresentationBundle.php");

    }


}