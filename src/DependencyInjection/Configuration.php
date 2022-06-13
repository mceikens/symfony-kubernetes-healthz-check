<?php
namespace SymfonyKubernetesHealthzCheck\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_kubernetes_healthz_checks');

        $treeBuilder->getRootNode()
            ->children()
                ->arrayNode('database')
                    ->children()
                        ->booleanNode('enabled')->end()
                        ->booleanNode('readinessprobe')->end()
                        ->booleanNode('livenessprobe')->end()
                    ->end()
                ->end()
                ->arrayNode('redis')
                    ->children()
                        ->booleanNode('enabled')->end()
                        ->booleanNode('readinessprobe')->end()
                        ->booleanNode('livenessprobe')->end()
                    ->end()
                ->end()
                ->arrayNode('environment')
                    ->children()
                        ->booleanNode('enabled')->end()
                        ->booleanNode('readinessprobe')->end()
                        ->booleanNode('livenessprobe')->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}