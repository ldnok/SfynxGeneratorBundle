<?php

namespace Sfynx\DddGeneratorBundle;

use Sfynx\DddGeneratorBundle\DependencyInjection\SfynxDddGeneratorExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class SfynxDddGeneratorBundle extends Bundle
{

    public function getContainerExtension()
    {
        return new SfynxDddGeneratorExtension();
    }
}
