<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Service\Manager;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class ManagerHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Domain/Service/Manager';
    const SKELETON_TPL = 'Manager.php.twig';

    protected $targetPattern = '%s/%s/Domain/Service/%s/Manager/%sManager.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['destinationPath'],
            $this->parameters['projectDir'],
            ucfirst($this->parameters['entityName']),
            ucfirst($this->parameters['entityName'])
        );
    }
}
