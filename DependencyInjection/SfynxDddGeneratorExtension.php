<?php

namespace Sfynx\DddGeneratorBundle\DependencyInjection;

use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Config\FileLocator;

class SfynxDddGeneratorExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/Api/config'));
        $loader->load('config.yml');
        $loader->load('services.yml');

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/Bundle/config'));
        $loader->load('config.yml');
        $loader->load('services.yml');
    }
}
