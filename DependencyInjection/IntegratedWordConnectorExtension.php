<?php
namespace Integrated\Bundle\WordConnectorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * IntegratedWordConnectorExtension for loading configuration
 *
 * @package Integrated\Bundle\WordConnectorBundle\DependencyInjection
 * @author Nizar Ellouze <integrated@e-active.nl>
 */
class IntegratedWordConnectorExtension extends Extension
{
    /**
     * Load the configuration
     *
     * @param array $configs
     * @param ContainerBuilder $container
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new Loader\XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.xml');
    }
  
}