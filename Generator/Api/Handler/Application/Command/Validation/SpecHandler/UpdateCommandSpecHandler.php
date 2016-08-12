<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Application\Command\Validation\SpecHandler;


use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class UpdateCommandSpecHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Application/Command/Handler/Validation/SpecHandler';
    const SKELETON_TPL = 'UpdateCommandSpecHandler.php.twig';

    protected $targetPattern = '%s/%s/Application/%s/Command/Validation/SpecHandler/UpdateCommandSpecHandler.php';
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
