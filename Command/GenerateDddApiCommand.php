<?php
declare(strict_types=1);

namespace Sfynx\DddGeneratorBundle\Command;

use InvalidArgumentException;

use Sfynx\DddGeneratorBundle\Generator\Api\DddApiGenerator;
use Sfynx\DddGeneratorBundle\Generator\Api\Generator\Application;
use Sfynx\DddGeneratorBundle\Generator\Api\Generator\Domain;
use Sfynx\DddGeneratorBundle\Generator\Api\Generator\Infrastructure;
use Sfynx\DddGeneratorBundle\Generator\Api\Generator\InfrastructureBundle;
use Sfynx\DddGeneratorBundle\Generator\Api\Generator\Presentation;
use Sfynx\DddGeneratorBundle\Generator\Api\Generator\PresentationBundle;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException as SFConsoleInvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException as SFConsoleLogicException;
use Symfony\Component\Console\Exception\RuntimeException as SFConsoleRuntimeException;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Yaml\Exception\ParseException as SFYMLParseException;
use Symfony\Component\Yaml\Parser;

/**
 * Class GenerateDddApiCommand.
 *
 * @category Command
 */
class GenerateDddApiCommand extends Command
{
    /** @var DddApiGenerator */
    protected $generator;
    /** @var array */
    protected $config;

    protected $actionsToCreate;
    protected $rootDir;
    protected $destinationPath;
    protected $contextName;


    protected $entities = [];
    protected $entitiesToCreate = [];
    protected $valueObjects = [];
    protected $valueObjectsToCreate = [];
    protected $paths = [];
    protected $pathsToCreate = [];

    /**
     * Set the generator to use.
     *
     * @param DddApiGenerator $generator
     * @return GenerateDddApiCommand
     */
    public function setGenerator(DddApiGenerator $generator): self
    {
        $this->generator = $generator;
        return $this;
    }

    /**
     * Set the configuration file to use for the generator. This file must be a YML file, containing a Swagger compliant
     * snippet that can define a valid PHP array.
     *
     * @param array $config
     * @return GenerateDddApiCommand
     */
    public function setConfig(array $config): self
    {
        $this->config = $config;
        return $this;
    }

    /**
     * Set the root dir based upon the argument sent.
     *
     * @param string $rootDir
     * @return GenerateDddApiCommand
     */
    public function setRootDir(string $rootDir): self
    {
        $this->rootDir = str_replace(DIRECTORY_SEPARATOR . 'app', '', $rootDir);
        return $this;
    }

    /**
     * Configure the current command.
     *
     * @see Command::configure()
     * @throws InvalidArgumentException When the name is invalid
     */
    public function configure()
    {
        $this->setName('sfynx:api')
            ->setDescription('Generate a DDD Rest API.')
            ->addArgument('context-name', InputArgument::REQUIRED, 'Context name.')
            ->addArgument('path-to-swagger-file', InputArgument::OPTIONAL, 'Path to swagger yml file.')
            ->addArgument('destination-path', InputArgument::OPTIONAL, 'Destination path.', '/tmp')
            ->addOption('create-all', null, InputOption::VALUE_NONE, 'Generate all items.')
            ->setHelp('Generate a DDD Rest API for an entity or a list of entities.');
    }

    /**
     * Interacts with the user.
     *
     * @see Command::interact()
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws SFConsoleInvalidArgumentException
     * @throws SFConsoleLogicException
     * @throws SFConsoleRuntimeException
     */
    public function interact(InputInterface $input, OutputInterface $output)
    {
        $this->setArgumentPathToSwaggerFile($input, $output)
            ->setArgumentDestinationPath($input, $output)
            ->setArgumentContextName($input, $output)
            ->parseSwaggerFile($input, $output);
    }

    /**
     * Main function, execute the generator.
     *
     * @see Command::execute()
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int The error code of the execution. 0 if no error.
     */
    public function execute(InputInterface $input, OutputInterface $output): int
    {
        //TODO refactor.
        $projectDir = $input->getArgument('context-name');
        $destinationPath = $input->getArgument('destination-path');

        $applicationGenerator = new Application($this->generator, $this->entities, $this->entitiesToCreate, $this->valueObjects, $this->valueObjectsToCreate, $this->paths, $this->pathsToCreate, $this->rootDir, $projectDir, $destinationPath, $output);
        $domainGenerator = new Domain($this->generator, $this->entities, $this->entitiesToCreate, $this->valueObjects, $this->valueObjectsToCreate, $this->paths, $this->pathsToCreate, $this->rootDir, $projectDir, $destinationPath, $output);
        $presentationGenerator = new Presentation($this->generator, $this->entities, $this->entitiesToCreate, $this->valueObjects, $this->valueObjectsToCreate, $this->paths, $this->pathsToCreate, $this->rootDir, $projectDir, $destinationPath, $output);

        $presentationBundleGenerator = new PresentationBundle($this->generator, $this->entities, $this->entitiesToCreate, $this->valueObjects, $this->valueObjectsToCreate, $this->paths, $this->pathsToCreate, $this->rootDir, $projectDir, $destinationPath, $output);

        $infrastructureGenerator = new Infrastructure($this->generator, $this->entities, $this->entitiesToCreate, $this->valueObjects, $this->valueObjectsToCreate, $this->paths, $this->pathsToCreate, $this->rootDir, $projectDir, $destinationPath, $output);
        $infrastructureBundleGenerator = new InfrastructureBundle($this->generator, $this->entities, $this->entitiesToCreate, $this->valueObjects, $this->valueObjectsToCreate, $this->paths, $this->pathsToCreate, $this->rootDir, $projectDir, $destinationPath, $output);

        echo ' # # # # # ' . __FILE__ . ':' . __LINE__ . ': Uncomment these lines after developping system.';

        $applicationGenerator->generate();
        $domainGenerator->generate();
        $presentationGenerator->generate();
        $presentationBundleGenerator->generate();

        $infrastructureGenerator->generate();
        exit;
        $infrastructureBundleGenerator->generate();

        return 0;
    }

    /**
     * Parse the swaggerFile read entity, route and actions.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     * @throws SFYMLParseException
     */
    protected function parseSwaggerFile(InputInterface $input, OutputInterface $output)
    {
        $this->config = (new Parser())->parse(file_get_contents($input->getArgument('path-to-swagger-file')));

        $this->buildAllValueObjects($input, $output)
            ->buildAllEntities($input, $output)
            ->buildAllRoutes($input, $output);
    }

    /**
     * Parse route from a parsed Swagger File
     *
     * Create a route with needed information
     */
    protected function parseRoutes()
    {
        $results = [];
        foreach ($this->config["paths"] as $path => $data) {
            foreach ($data as $verb => $verbData) {
                $result = [];
                $result["verb"] = $verb;
                $result["action"] = $verbData["operationId"];
                $result["description"] = $verbData["description"];
                $result["controller"] = $verbData["x-controller"];
                $result["entity"] = $verbData["x-entity"];
                $results[$path][$verb] = $result;
            }
        }

        return $results;
    }

    /**
     * Parse Value object from a swagger file
     */
    protected function parseValueObjects()
    {
        $results = [];
        if (isset($this->config['x-valueObjects'])) {
            foreach ($this->config['x-valueObjects'] as $name => $data) {
                if (!isset($results[$name])) {
                    $results[$name] = [];
                }
                $results[$name]['type'] = $data['type'];
                $results[$name]['name'] = $data['name'];
                foreach ($data['x-fields'] as $field) {
                    $results[$name]['fields'][] = $field;
                }
            }
        }

        return $results;
    }

    /**
     * Parse entities from a swagger file
     */
    protected function parseEntities()
    {
        $results = [];
        if (isset($this->config['x-entities'])) {
            foreach ($this->config['x-entities'] as $name => $data) {
                foreach ($data['x-fields'] as $field) {
                    $results[$name][$field['name']] = $field;
                }
            }
        }

        return $results;
    }

    /**
     * Set the argument 'path-to-swagger-file'. Use environment variable to set it, or ask to end user if the
     * environment variable is not set.
     * Name of the environment variable to use is "SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE"
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return GenerateDddApiCommand
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     */
    protected function setArgumentPathToSwaggerFile(InputInterface $input, OutputInterface $output): self
    {
        if (isset($_SERVER['SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE'])) {
            $input->setArgument('path-to-swagger-file', $_SERVER['SYMFONY_SFYNX_PATH_TO_SWAGGER_FILE']);
            return $this;
        }

        $question = new Question('Path to swagger yml file: ');
        $pathToSwaggerEntityFile = $this->getQuestionHelper()->ask($input, $output, $question);

        while (!is_file($pathToSwaggerEntityFile)) {
            $output->writeln('This file does not exist.');

            //Todo: check the existence of the entity-name

            $pathToSwaggerEntityFile = $this->getQuestionHelper()->ask($input, $output, $question);
        }

        $input->setArgument('path-to-swagger-file', $pathToSwaggerEntityFile);
        return $this;
    }

    /**
     * Set the argument 'destination-path'. Use environment variable to set it, or ask to end user if the
     * environment variable is not set.
     * Name of the environment variable to use is "SYMFONY_SFYNX_PATH_TO_DEST_FILES"
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return GenerateDddApiCommand
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     */
    protected function setArgumentDestinationPath(InputInterface $input, OutputInterface $output): self
    {
        if (isset($_SERVER['SYMFONY_SFYNX_PATH_TO_DEST_FILES'])) {
            $input->setArgument('destination-path', $_SERVER['SYMFONY_SFYNX_PATH_TO_DEST_FILES']);
            return $this;
        }

        $question = new Question('Destination path: ');
        $destinationPath = $this->getQuestionHelper()->ask($input, $output, $question);

        while (!is_dir($destinationPath) || !is_writable($destinationPath)) {
            $output->writeln('This directory does not exist or is not writable.');
            $destinationPath = $this->getQuestionHelper()->ask($input, $output, $question);
        }

        $input->setArgument('destination-path', $destinationPath);
        return $this;
    }

    /**
     * Set the argument 'context-name'. Use environment variable to set it, or ask to end user if the
     * environment variable is not set.
     * Name of the environment variable to use is "SYMFONY_SFYNX_CONTEXT_NAME"
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return GenerateDddApiCommand
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     */
    protected function setArgumentContextName(InputInterface $input, OutputInterface $output): self
    {
        if (isset($_SERVER['SYMFONY_SFYNX_CONTEXT_NAME'])) {
            $input->setArgument('context-name', $_SERVER['SYMFONY_SFYNX_CONTEXT_NAME']);
            return $this;
        }
        $contextName = $this->getQuestionHelper()->ask($input, $output, new Question('Context name: '));
        $input->setArgument('context-name', $contextName);

        return $this;
    }

    /**
     * Set properties "valueObjects" and "valueObjectsToCreate" after parsing the value objects defined in the YML file.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return GenerateDddApiCommand
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     */
    protected function buildAllValueObjects(InputInterface $input, OutputInterface $output): self
    {
        $this->valueObjects = $this->parseValueObjects();

        $valueObjectNames = array_keys($this->valueObjects);

        //If option "create-all" is set, we take all value objects.
        if ($input->getOption('create-all')) {
            $this->valueObjectsToCreate = $valueObjectNames;
            return $this;
        }

        //Otherwise, loop for each value object and ask to end user.
        $dialog = $this->getQuestionHelper();
        $questionSentence = 'Do you want to create the valueObject "%s" ? [Y/n]' . PHP_EOL;
        foreach ($valueObjectNames as $voName) {
            if ($dialog->ask($input, $output, new Question(sprintf($questionSentence, $voName)))) {
                $this->valueObjectsToCreate[] = $voName;
            }
        }

        return $this;
    }

    /**
     * Set properties "entities" and "entitiesToCreate" after parsing the entities defined in the YML file.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return GenerateDddApiCommand
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     */
    protected function buildAllEntities(InputInterface $input, OutputInterface $output): self
    {
        $this->entities = $this->parseEntities();

        //If option "create-all" is set, we take all entities.
        if ($input->getOption('create-all')) {
            $this->entitiesToCreate = $this->entities;
            return $this;
        }

        //Otherwise, loop for each entity and ask to end user.
        $dialog = $this->getQuestionHelper();
        $questionSentence = 'Do you want to create the entity "%s" ? [Y/n]' . PHP_EOL;
        foreach ($this->entities as $entityName => $fields) {
            if ($dialog->ask($input, $output, new Question(sprintf($questionSentence, $entityName)))) {
                $this->entitiesToCreate[$entityName] = $fields;
            }
        }

        return $this;
    }

    /**
     * Set properties "paths" and "pathsToCreate" after parsing the routes defined in the YML file.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     * @return GenerateDddApiCommand
     * @throws SFConsoleRuntimeException
     * @throws SFConsoleLogicException
     * @throws SFConsoleInvalidArgumentException
     */
    protected function buildAllRoutes(InputInterface $input, OutputInterface $output): self
    {
        $this->paths = $this->parseRoutes();

        //If option "create-all" is set, we take all routes.
        if ($input->getOption('create-all')) {
            $this->pathsToCreate = $this->paths;
            return $this;
        }

        //Otherwise, loop for each route and ask to end user.
        $dialog = $this->getQuestionHelper();
        $questionSentence = 'Do you want create the %s action for route %s and verb %s? [Y/n]' . PHP_EOL;
        foreach ($this->paths as $path => $verbData) {
            foreach ($verbData as $verb => $data) {
                $question = new Question(sprintf($questionSentence, $data['action'], $path, $verb));
                if ($dialog->ask($input, $output, $question)) {
                    $this->pathsToCreate[$path][$verb] = $data;
                }
            }
        }

        return $this;
    }

    /**
     * Retrieve the Symfony QuestionHelper object.
     *
     * @return QuestionHelper
     * @throws SFConsoleInvalidArgumentException
     * @throws SFConsoleLogicException
     */
    private function getQuestionHelper(): QuestionHelper
    {
        return $this->getHelper('question');
    }
}
