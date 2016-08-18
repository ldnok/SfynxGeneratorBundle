<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Tests\Application\Entity\Command;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class DeleteCommandTestHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Tests/Application/Entity/Command';
    const SKELETON_TPL = 'DeleteCommandTest.php.twig';

    protected $targetPattern = '%s/%s/Tests/Application/Entity/Command/DeleteCommandTest.php';
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