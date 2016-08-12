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

class InfrastructureTest extends \PHPUnit_Framework_TestCase
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
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence");
        $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository");

        foreach($this->entities as $entity=>$fields) {
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity);
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/DeleteManyRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/DeleteRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/GetAllRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/GetRepository.php");

            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Orm");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Orm/DeleteManyRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Orm/DeleteRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Orm/GetAllRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Orm/GetRepository.php");

            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/DeleteManyRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/DeleteRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/Odm/GetAllRepository.php");
            $this->assertFileExists(__DIR__ . "/../../../../src/" . $this->environment . "/Infrastructure/Persistence/Repository/".$entity."/TraitEntityName.php");
        }

    }


}