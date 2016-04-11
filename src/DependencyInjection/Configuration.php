<?php

/*
 * This file is part of the WhteRbtFileInspectionsBundle.
 *
 * Copyright (c) 2016 Marcel Kraus <mail@marcelkraus.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace WhteRbt\FileInspectionsBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('whte_rbt_file_inspections');

        $rootNode
            ->children()
                ->arrayNode('namespaces')
                    ->requiresAtLeastOneElement()
                    ->defaultValue(['WhteRbt\FileInspectionsBundle\Inspector'])
                    ->prototype('scalar')->end()
                ->end()
                ->arrayNode('queue')
                    ->isRequired()
                    ->requiresAtLeastOneElement()
                    ->prototype('array')
                        ->children()
                            ->scalarNode('path')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->scalarNode('filename')
                                ->isRequired()
                                ->cannotBeEmpty()
                            ->end()
                            ->arrayNode('inspections')
                                ->isRequired()
                                ->requiresAtLeastOneElement()
                                ->useAttributeAsKey('id')
                                ->prototype('array')
                                    ->children()
                                        ->booleanNode('active')
                                            ->isRequired()
                                        ->end()
                                        ->arrayNode('attributes')
                                            ->isRequired()
                                            ->prototype('scalar')->end()
                                        ->end()
                                    ->end()
                                ->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}
