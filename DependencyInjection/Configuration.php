<?php
namespace Integrated\Bundle\WordConnectorBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder,
    Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Configuration class for WordConnectorBundle
 *
 * @package Integrated\Bundle\WordConnectorBundle\DependencyInjection
 * @author Nizar Ellouze <integrated@e-active.nl>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $treeBuilder->root('integrated_word_connector');
        return $treeBuilder;
    }
}