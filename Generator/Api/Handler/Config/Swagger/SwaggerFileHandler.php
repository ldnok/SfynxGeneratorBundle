<?php

namespace Sfynx\DddGeneratorBundle\Generator\Api\Handler\Domain\Entity;

use Sfynx\DddGeneratorBundle\Generator\Generalisation\AbstractHandler;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\HandlerInterface;
use Sfynx\DddGeneratorBundle\Generator\Generalisation\ExecuteTrait;
use Sfynx\DddGeneratorBundle\Util\PiFileManager;

class SwaggerFileHandler implements HandlerInterface
{
    protected $target;
    protected $swaggerSource;

    public function __construct($swaggerSource, $target)
    {
        $this->swaggerSource = $swaggerSource;
        $this->target = $target;
    }

    public function execute()
    {
        $targetDir = PiFileManager::getFileDirname($this->target);
        if (!file_exists(PiFileManager::getFileDirname($this->target))) {
            mkdir($targetDir, 0777, true);
        }

        file_put_contents($this->target, $this->swaggerSource);
        $this->setPermissions($this->target);
    }

    protected function setPermissions($target, $owner = 'www-data', $group = 'www-data', $rights = 0777)
    {
        chown($target, $owner);
        chgrp($target, $group);
        chmod($target, $rights);
    }
}
