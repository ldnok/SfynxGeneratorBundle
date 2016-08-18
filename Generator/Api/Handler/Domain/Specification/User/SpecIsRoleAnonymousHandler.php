<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Specification\User;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class SpecIsRoleAnonymousHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Domain/Specification/User';
    const SKELETON_TPL = 'SpecIsRoleAnonymous.php.twig';

    protected $targetPattern = '%s/%s/Domain/Specification/Infrastructure/User/SpecIsRoleAnonymous.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['destinationPath'],
            $this->parameters['projectDir']
        );
    }
}
