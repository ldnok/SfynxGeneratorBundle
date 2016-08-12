<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Adapter;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class GetAllAdapterHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Presentation/Adapter';
    const SKELETON_TPL = 'GetAllQueryAdapter.php.twig';

    protected $targetPattern = '%s/%s/Presentation/Adapter/%s/Query/GetAllQueryAdapter.php';
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
