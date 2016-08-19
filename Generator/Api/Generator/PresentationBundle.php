<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Generator;

use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\DependencyInjection\PresentationBundleExtensionHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\PresentationBundleHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\DependencyInjection\ConfigurationHandler as PBConfigurationHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\DependencyInjection\Compiler\ResettingListenersPassHandler as PBResettingListenersPass;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\Resources\ApplicationHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\Resources\RoutesHandler;

class PresentationBundle
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
        $this->output->writeln("# GENERATE PRESENTATIONBUNDLE  STRUCTURE    #");
        $this->output->writeln("#############################################");

        $this->generateBundle();
        $this->generateConfigurations();
    }


    public function generateBundle()
    {
        $parameters = [
            'rootDir' => $this->rootDir . "/src",
            'projectDir' => $this->projectDir,
            'projectName' => str_replace('src/', '', $this->projectDir),
            'entities' => $this->entities,
            'destinationPath' => $this->destinationPath,
        ];

        $this->generator->addHandler(new PresentationBundleHandler($parameters));
        $this->generator->addHandler(new PresentationBundleExtensionHandler($parameters));
        $this->generator->addHandler(new PBConfigurationHandler($parameters));
        $this->generator->addHandler(new PBResettingListenersPass($parameters));

        $this->generator->execute();
        $this->generator->clear();
    }


    /**
     * Generates routes.yml and entities services configuration file
     */
    public function generateConfigurations()
    {
        foreach ($this->entities as $entity => $vo) {
            $parameters = [
                'rootDir' => $this->rootDir . "/src",
                'projectDir' => $this->projectDir,
                'projectName' => str_replace('src/', '', $this->projectDir),
                'routes' => $this->paths,
                'entityName' => $entity,
                'destinationPath' => $this->destinationPath,
            ];

            $this->generator->addHandler(new RoutesHandler($parameters));
            $this->generator->addHandler(new ApplicationHandler($parameters));

            $this->generator->execute();
            $this->generator->clear();
        }
    }

}