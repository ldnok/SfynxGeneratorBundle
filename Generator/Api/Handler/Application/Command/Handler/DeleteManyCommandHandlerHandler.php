<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Application\Command\Handler;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class DeleteManyCommandHandlerHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Application/Command/Handler';
    const SKELETON_TPL = 'DeleteManyCommandHandler.php.twig';

    protected $targetPattern = '%s/%s/Application/%s/Command/Handler/DeleteManyCommandHandler.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['rootDir'],
            $this->parameters['projectDir'],
            ucfirst($this->parameters['entityName'])
        );
    }
}
