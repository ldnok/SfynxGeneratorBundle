<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Repository;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class EntityRepositoryInterfaceHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Domain/Repository';
    const SKELETON_TPL = 'EntityRepositoryInterface.php.twig';

    protected $targetPattern = '%s/%s/Domain/Repository/%sRepositoryInterface.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['rootDir'],
            $this->parameters['projectDir'],
            ucfirst($this->parameters['entityName']),
            ucfirst($this->parameters['entityName'])
        );
    }
}
