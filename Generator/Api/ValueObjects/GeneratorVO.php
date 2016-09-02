<?php
declare(strict_types=1);

namespace Sfynx\DddGeneratorBundle\Generator\Api\ValueObjects;

use Sfynx\DddGeneratorBundle\Generator\Api\DddApiGenerator;

/**
 * Class GeneratorVO
 *
 * @category Generator
 * @package Api
 * @subpackage ValueObjects
 */
final class GeneratorVO
{
    /** @var DddApiGenerator */
    private $generator;
    /** @var array[] */
    private $entitiesToCreate = [];
    /** @var array[] */
    private $valueObjectsToCreate = [];
    /** @var array[] */
    private $pathsToCreate = [];
    /** @var string */
    private $rootDir;
    /** @var string */
    private $projectDir;
    /** @var string */
    private $destinationPath;

    /**
     * GeneratorVO constructor.
     *
     * @param DddApiGenerator    $generator
     * @param ElementsToCreateVO $elementsToCreate
     * @param PathsVO            $paths
     */
    public function __construct(DddApiGenerator $generator, ElementsToCreateVO $elementsToCreate, PathsVO $paths)
    {
        $this->generator = $generator;

        $this->entitiesToCreate = $elementsToCreate->getEntities();
        $this->valueObjectsToCreate = $elementsToCreate->getValueObjects();
        $this->pathsToCreate = $elementsToCreate->getPaths();

        $this->rootDir = $paths->getRootDir();
        $this->projectDir = $paths->getProjectDir();
        $this->destinationPath = $paths->getDestinationPath();
    }

    /**
     * @return DddApiGenerator
     */
    public function getGenerator(): DddApiGenerator
    {
        return $this->generator;
    }

    /**
     * @return array
     */
    public function getEntitiesToCreate(): array
    {
        return $this->entitiesToCreate;
    }

    /**
     * @return array
     */
    public function getValueObjectsToCreate(): array
    {
        return $this->valueObjectsToCreate;
    }

    /**
     * @return array
     */
    public function getPathsToCreate(): array
    {
        return $this->pathsToCreate;
    }

    /**
     * @return string
     */
    public function getRootDir(): string
    {
        return $this->rootDir;
    }

    /**
     * @return string
     */
    public function getProjectDir(): string
    {
        return $this->projectDir;
    }

    /**
     * @return string
     */
    public function getDestinationPath(): string
    {
        return $this->destinationPath;
    }
}
