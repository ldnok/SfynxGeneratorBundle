<?php
declare(strict_types = 1);

namespace Sfynx\DddGeneratorBundle\Generator\Api\ValueObjects;

/**
 * Class PathsVO
 *
 * @category Generator
 * @package Api
 * @subpackage ValueObjects
 */
final class PathsVO
{
    /** @var string */
    private $rootDir;
    /** @var string */
    private $projectDir;
    /** @var string */
    private $destinationPath;

    /**
     * PathsVO constructor.
     *
     * @param string $rootDir
     * @param string $projectDir
     * @param string $destinationPath
     */
    public function __construct(string $rootDir, string $projectDir, string $destinationPath)
    {
        $this->rootDir = $rootDir;
        $this->projectDir = $projectDir;
        $this->destinationPath = $destinationPath;
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
