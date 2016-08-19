<?php
namespace Sfynx\DddGeneratorBundle\Generator\Api\Generator;


use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Orm\DeleteManyRepositoryHandler as IPDeleteManyRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Orm\DeleteRepositoryHandler as IPDeleteRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Orm\GetAllRepositoryHandler as IPGetAllRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Orm\GetByIdsRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Orm\GetRepositoryHandler as IPGetRepositoryHandler;


use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Odm\DeleteManyRepositoryHandler as IPODMDeleteManyRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Odm\DeleteRepositoryHandler as IPODMDeleteRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Odm\GetAllRepositoryHandler as IPODMGetAllRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Odm\GetRepositoryHandler as IPODMGetRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Odm\GetIdsRepositoryHandler as IPODMGetIdsRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\Orm\SearchByRepositoryHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Infrastructure\Persistence\TraitEntityNameHandler;

class Infrastructure
{
    protected $generator;
    protected $entities = [];
    protected $entitiesToCreate = [];
    protected $valueObjects = [];
    protected $valueObjectsToCreate = [];
    protected $paths = [];
    protected $pathsToCreate = [];
    protected $projectDir;
    protected $destinationPath;

    public function __construct($generator, $entities, $entitiesToCreate, $valueObjects, $valueObjectsToCreate, $paths, $pathsToCreate, $rootDir, $projectDir, $destinationPath, $output)
    {
        $this->generator = $generator;
        $this->destinationPath = $destinationPath;
        $this->output = $output;
        $this->entities = $entities;
        $this->entitiesToCreate = $entitiesToCreate;
        $this->valueObjects = $valueObjects;
        $this->valueObjectsToCreate = $valueObjectsToCreate;
        $this->paths = $paths;
        $this->pathsToCreate = $pathsToCreate;
        $this->projectDir = $projectDir;
        $this->rootDir = $rootDir;
    }

    public function generate()
    {
        $this->output->writeln("#############################################");
        $this->output->writeln("# GENERATE INFRASTRUCTURE STRUCTURE         #");
        $this->output->writeln("#############################################");

        $this->generatePersistence();
    }

    /**
     *
     * Generate :
     * /Infrastructure/Persistence/Odm/CustomRepository.php
     * /Infrastructure/Persistence/Odm/DeleteManyRepository.php
     * /Infrastructure/Persistence/Odm/DeleteRepository.php
     * /Infrastructure/Persistence/Odm/GetAllRepository.php
     * /Infrastructure/Persistence/Odm/GetRepository.php
     * /Infrastructure/Persistence/Odm/SearchByRepository.php
     *
     *
     * /Infrastructure/Persistence/Orm/DeleteManyRepository.php
     * /Infrastructure/Persistence/Orm/DeleteRepository.php
     * /Infrastructure/Persistence/Orm/GetAllRepository.php
     * /Infrastructure/Persistence/Orm/GetRepository.php
     */
    public function generatePersistence()
    {

        foreach ($this->pathsToCreate as $route => $verbData) {
            foreach ($verbData as $verb => $data) {

                $constructorParams = $managerArgs = "";
                foreach ($this->entities[$data["entity"]] as $field) {
                    $constructorParams .= "$" . $field['name'] . ",";
                    $managerArgs .= "$" . $field['name'] . ",";
                }

                $parameters = [
                    'rootDir' => $this->rootDir . "/src",
                    'projectDir' => $this->projectDir,
                    'projectName' => str_replace('src/', '', $this->projectDir),
                    'actionName' => ucfirst(strtolower($data['action'])),
                    'entityName' => ucfirst(strtolower($data['entity'])),
                    'entityFields' => $this->entities[$data['entity']],
                    'managerArgs' => trim($managerArgs, ','),
                    'fields' => $this->entities[$data['entity']],
                    'valueObjects' => $this->valueObjects,
                    'constructorArgs' => trim($constructorParams, ','),
                    'destinationPath' => $this->destinationPath,
                ];

                $this->generator->addHandler(new IPDeleteManyRepositoryHandler($parameters));
                $this->generator->addHandler(new IPODMDeleteManyRepositoryHandler($parameters));

                $this->generator->addHandler(new IPDeleteRepositoryHandler($parameters));
                $this->generator->addHandler(new IPODMDeleteRepositoryHandler($parameters));

                $this->generator->addHandler(new IPGetAllRepositoryHandler($parameters));
                $this->generator->addHandler(new IPODMGetAllRepositoryHandler($parameters));

                $this->generator->addHandler(new IPGetRepositoryHandler($parameters));
                $this->generator->addHandler(new IPODMGetRepositoryHandler($parameters));

                $this->generator->addHandler(new GetByIdsRepositoryHandler($parameters));

                $this->generator->addHandler(new SearchByRepositoryHandler($parameters));

                $this->generator->addHandler(new TraitEntityNameHandler($parameters));

                $this->generator->execute();
                $this->generator->clear();
            }
        }
    }
}