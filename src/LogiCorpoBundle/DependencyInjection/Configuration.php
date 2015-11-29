<?php

namespace LogiCorpoBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('logi_corpo');


        $treeBuilder = new TreeBuilder();
        $itemsNode = $treeBuilder->root('menu_logicorpo:');
/*
        $rootNode
            ->children()
                ->arrayNode('items')
                    ->addDefaultsIfNotSet()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('label')->end()
                            ->scalarNode('route')->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('roles')
                    ->addDefaultsIfNotSet()
                    ->prototype('array')
                        ->children()
                            
                            ->prototype('scalar')->end()
                        ->end()
                    ->end()

                ->end()
            ->end();
*/
        return $treeBuilder;
    }
}
