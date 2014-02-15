<?php
namespace Integrated\Bundle\WordConnectorBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * IntegratedContentExtension for loading configuration
 *
 * @package Integrated\Bundle\ContentBundle\DependencyInjection
 * @author Jeroen van Leeuwen <jeroen@e-active.nl>
 */
class IntegratedWordConnectorExtension extends Extension implements PrependExtensionInterface
{
    /**
     * @var string
     */
    protected $formTemplate = 'IntegratedContentBundle:Form:form_div_layout.html.twig';

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

        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);
    }

    /**
     * {@inheritDoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $this->configureTwigBundle($container);
    }

    /**
     * @param ContainerBuilder $container The service container
     *
     * @return void
     */
    protected function configureTwigBundle(ContainerBuilder $container)
    {
        foreach ($container->getExtensions() as $name => $extension) {
            switch ($name) {
                case 'twig':
                    $container->prependExtensionConfig(
                        $name,
                        array('form'  => array('resources' => array($this->formTemplate)))
                    );
                    break;
            }
        }
    }
}