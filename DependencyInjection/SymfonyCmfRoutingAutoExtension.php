<?php

namespace Symfony\Cmf\Bundle\RoutingAutoBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

class SymfonyCmfRoutingAutoExtension extends Extension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $processor = new Processor();
        $configuration = new Configuration();

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('auto_route.xml');
        $loader->load('path_provider.xml');
        $loader->load('exists_action.xml');
        $loader->load('not_exists_action.xml');

        $config = $processor->processConfiguration($configuration, $configs);
        $chainFactoryDef = $container->getDefinition('symfony_cmf_routing_auto.factory');

        // normalize configuration
        foreach ($config['auto_route_mapping'] as $classFqn => $mapping) {
            $chainFactoryDef->addMethodCall('registerMapping', array($classFqn, $mapping));
        }

        $container->setParameter($this->getAlias().'.route_base_path', $config['route_base_path']);
    }
}
