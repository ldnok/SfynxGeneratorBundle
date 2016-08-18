<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Tests\Application\Entity\Command\Handler;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class DeleteCommandHandlerTestHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Tests/Application/Entity/Command/Handler';
    const SKELETON_TPL = 'DeleteCommandHandlerTest.php.twig';

    protected $targetPattern = '%s/%s/Tests/Application/Entity/Command/Handler/DeleteCommandHandlerTest.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['rootDir'],
            $this->parameters['projectDir'],
            ucfirst($this->parameters['entityName']),
            ucfirst($this->parameters['actionName'])
        );
    }
}
