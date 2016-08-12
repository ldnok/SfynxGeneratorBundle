<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\ValueObject;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class ValueObjectCompositeHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Application/ValueObject';
    const SKELETON_TPL = 'ValueObjectComposite.php.twig';

    protected $targetPattern = '%s/%s/Domain/ValueObject/%s.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['rootDir'],
            $this->parameters['projectDir'],
            ucfirst($this->parameters['voName'])
        );
    }
}
