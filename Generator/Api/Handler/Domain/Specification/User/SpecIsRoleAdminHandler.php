<?php
namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Specification\User;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;

class SpecIsRoleAdminHandler extends AbstractHandler implements HandlerInterface
{
    use  ExecuteTrait;

    const SKELETON_DIR = 'Api/Domain/Specification/User';
    const SKELETON_TPL = 'SpecIsRoleAdmin.php.twig';

    protected $targetPattern = '%s/%s/Domain/Specification/Infrastructure/User/SpecIsRoleAdmin.php';
    protected $target;

    protected function setTarget()
    {
        $this->target = sprintf(
            $this->targetPattern,
            $this->parameters['rootDir'],
            $this->parameters['projectDir']
        );
    }
}
