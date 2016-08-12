<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Generator;

use Sfynx\DddGeneratorBundle\Generator\Api\Handler\InfrastructureBundle\DependencyInjection\Compiler\CreateRepositoryFactoryPassHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\InfrastructureBundle\DependencyInjection\ConfigurationHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\InfrastructureBundle\DependencyInjection\InfrastructureBundleExtensionHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\InfrastructureBundle\InfrastructureBundleHandler;

class InfrastructureBundle
{
    protected $generator;
    protected $entities = [];
    protected $entitiesToCreate = [];
    protected $valueObjects = [];
    protected $valueObjectsToCreate = [];
    protected $paths = [];
    protected $pathsToCreate = [];
    protected $projectDir;

    public function __construct($generator, $entities, $entitiesToCreate, $valueObjects, $valueObjectsToCreate, $paths, $pathsToCreate, $rootDir, $projectDir, $output)
    {
        $this->output = $output;
        $this->entities = $entities;
        $this->entitiesToCreate = $entitiesToCreate;
        $this->valueObjects = $valueObjects;
        $this->valueObjectsToCreate = $valueObjectsToCreate;
        $this->paths = $paths;
        $this->pathsToCreate = $pathsToCreate;
        $this->projectDir = $projectDir;
        $this->rootDir = $rootDir;
        $this->generator = $generator;
    }

    public function generate()
    {
        $this->output->writeln("#############################################");
        $this->output->writeln("# GENERATE INFRASTRUCTURE BUNDLE STRUCTURE  #");
        $this->output->writeln("#############################################");

        $this->generateBundle();
    }

    public function generateBundle()
    {
        $parameters = [
            'rootDir' => $this->rootDir . "/src",
            'projectDir' => $this->projectDir,
            'projectName' => str_replace('src/', '', $this->projectDir),
            'entities' => $this->entities,
            'valueObjects' => $this->valueObjects
        ];

        $this->generator->addHandler(new CreateRepositoryFactoryPassHandler($parameters));
        $this->generator->addHandler(new ConfigurationHandler($parameters));
        $this->generator->addHandler(new InfrastructureBundleExtensionHandler($parameters));
        $this->generator->addHandler(new InfrastructureBundleHandler($parameters));

        $this->generator->execute();
        $this->generator->clear();
    }
}