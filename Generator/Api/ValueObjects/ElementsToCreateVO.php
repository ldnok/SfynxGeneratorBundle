<?php
declare(strict_types=1);

namespace Sfynx\DddGeneratorBundle\Generator\Api\ValueObjects;

/**
 * Class ElementsToCreateVO.
 *
 * @category Generator
 * @package Api
 * @subpackage ValueObjects
 */
final class ElementsToCreateVO
{
    /** @var array[] */
    private $entities = [];
    /** @var array[] */
    private $valueObjects = [];
    /** @var array[] */
    private $paths = [];

    /**
     * ElementsToCreateVO constructor.
     *
     * @param array $entities
     * @param array $valueObjects
     * @param array $paths
     */
    public function __construct(array $entities, array $valueObjects, array $paths)
    {
        $this->entities = $entities;
        $this->valueObjects = $valueObjects;
        $this->paths = $paths;
    }

    /**
     * @return array[]
     */
    public function getEntities(): array
    {
        return $this->entities;
    }

    /**
     * @return array[]
     */
    public function getValueObjects(): array
    {
        return $this->valueObjects;
    }

    /**
     * @return array[]
     */
    public function getPaths(): array
    {
        return $this->paths;
    }
}
