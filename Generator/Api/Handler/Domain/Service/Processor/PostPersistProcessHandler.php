<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Service\Processor;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class PostPersistProcessHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Domain/Service/Processor';
    const SKELETON_TPL = 'ProPersistsProcess.php.twig';

    protected $targetPattern = '%s/%s/Domain/Service/%s/Processor/PostPersistProcess.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['destinationPath'],
            $this->parameters['projectDir'],
            ucfirst($this->parameters['entityName'])
        );
    }
}
