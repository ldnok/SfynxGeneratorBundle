<?php
namespace Sfynx\DddGeneratorBundle\Generator\Api\Generator;

use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\DeleteAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\DeleteManyAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\GetAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\GetAllAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\GetByIdsQueryAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\GetIdsQueryAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\NewAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\SearchByAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\SearchByQueryAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter\UpdateAdapterHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Presentation\Coordination\ControllerHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\PresentationBundle\Resources\ControllersHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\DeleteManyRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\DeleteRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\GetAllRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\GetByIdsRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\GetRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\NewRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\SearchByRequestHandler;
use Sfynx\DddGeneratorBundle\Generator\Api\Handler\Request\UpdateRequestHandler;

class Presentation
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
        $this->generator = $generator;
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
        $this->output->writeln("#     GENERATE PRESENTATION STRUCTURE       #");
        $this->output->writeln("#############################################");

        $this->generateAdapter();
        $this->generateCoordination();
        $this->generateRequest();
    }

    public function generateAdapter()
    {
        foreach ($this->pathsToCreate as $route => $verbData) {
            foreach ($verbData as $verb => $data) {
                $constructorParams = "";
                foreach ($this->entities[$data["entity"]] as $field) {
                    $constructorParams .= "$" . $field['name'] . ",";
                }
            }

            $parameters = [
                'rootDir' => $this->rootDir . "/src",
                'projectDir' => $this->projectDir,
                'projectName' => str_replace('src/', '', $this->projectDir),
                'actionName' => ucfirst(strtolower($data['action'])),
                'entityName' => ucfirst(strtolower($data['entity'])),
                'entityFields' => $this->entities[$data["entity"]],
            ];
            $parameters['constructorArgs'] = trim($constructorParams, ',');

            $this->generator->addHandler(new UpdateAdapterHandler($parameters));
            $this->generator->addHandler(new NewAdapterHandler($parameters));
            $this->generator->addHandler(new DeleteAdapterHandler($parameters));
            $this->generator->addHandler(new DeleteManyAdapterHandler($parameters));
            $this->generator->addHandler(new GetAllAdapterHandler($parameters));
            $this->generator->addHandler(new GetByIdsQueryAdapterHandler($parameters));
            $this->generator->addHandler(new SearchByQueryAdapterHandler($parameters));
            $this->generator->addHandler(new GetAdapterHandler($parameters));

            $this->generator->execute();
            $this->generator->clear();
        }
    }

    public function generateCoordination()
    {

        foreach ($this->pathsToCreate as $route => $verbData) {
            foreach ($verbData as $verb => $data) {
                $controllers[$data["controller"]][] = ["action" => $data["action"], "path" => $route, "method" => $verb, "entityName" => $data['entity']];
            }
        }


        foreach ($controllers as $controller => $data) {


            $parametersQuery = [
                'rootDir' => $this->rootDir . "/src",
                'projectDir' => $this->projectDir,
                'projectName' => str_replace('src/', '', $this->projectDir),
                'controllerName' => $controller,
                'group' => "Query"
            ];

            $parametersCommand = [
                'rootDir' => $this->rootDir . "/src",
                'projectDir' => $this->projectDir,
                'projectName' => str_replace('src/', '', $this->projectDir),
                'controllerName' => $controller,
                'group' => "Command"
            ];


            foreach ($data as $action) {

                if (in_array($action["action"], ["put", "delete", "update", "new", "patch"])) {
                    $parametersCommand["controllerData"][] = $action;
                    $parametersCommand["entityName"] = $action["entityName"];

                    $this->generator->addHandler(new ControllerHandler($parametersCommand));
                    $this->generator->execute();
                    $this->generator->clear();

                } else {
                    $parametersQuery["controllerData"][] = $action;
                    $parametersQuery["entityName"] = $action["entityName"];

                    $this->generator->addHandler(new ControllerHandler($parametersQuery));
                    $this->generator->execute();
                    $this->generator->clear();

                }

                $controllerToCreate[$controller][$action["entityName"]] = true;
            }

            $controllersToCreate[] = $controllerToCreate;


        }


        /**
         * Generate controllers.yml
         */

        foreach ($controllersToCreate as $controller => $entities) {
            $parameters = [
                'rootDir' => $this->rootDir . "/src",
                'projectDir' => $this->projectDir,
                'projectName' => str_replace('src/', '', $this->projectDir),
                'controllers' => $controllersToCreate[$controller],
            ];

            $this->generator->addHandler(new ControllersHandler($parameters));

            $this->generator->execute();
            $this->generator->clear();
        }
    }


    public function generateRequest()
    {
        foreach ($this->pathsToCreate as $route => $verbData) {
            foreach ($verbData as $verb => $data) {

                $constructorParams = "";
                foreach ($this->entities[$data["entity"]] as $field) {
                    $constructorParams .= "$" . $field['name'] . ",";
                }


                $parameters = [
                    'rootDir' => $this->rootDir . "/src",
                    'projectDir' => $this->projectDir,
                    'projectName' => str_replace('src/', '', $this->projectDir),
                    'actionName' => ucfirst(strtolower($data['action'])),
                    'entityName' => ucfirst(strtolower($data['entity'])),
                    'entityFields' => $this->entities[$data["entity"]],
                ];
                $parameters['constructorArgs'] = trim($constructorParams, ',');


            }

            $this->generator->addHandler(new UpdateRequestHandler($parameters));
            $this->generator->addHandler(new NewRequestHandler($parameters));
            $this->generator->addHandler(new DeleteRequestHandler($parameters));
            $this->generator->addHandler(new DeleteManyRequestHandler($parameters));
            $this->generator->addHandler(new GetAllRequestHandler($parameters));
            $this->generator->addHandler(new SearchByRequestHandler($parameters));
            $this->generator->addHandler(new GetByIdsRequestHandler($parameters));
            $this->generator->addHandler(new GetRequestHandler($parameters));

            $this->generator->execute();
            $this->generator->clear();
        }

    }


}