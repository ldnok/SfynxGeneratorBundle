<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Workflow\Listener;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class WFGenerateVOListenerHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Domain/Workflow/Listener';
    const SKELETON_TPL = 'WFGenerateVOListener.php.twig';

    protected $targetPattern = '%s/%s/Domain/Workflow/%s/Listener/WFGenerateVOListener.php';
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
