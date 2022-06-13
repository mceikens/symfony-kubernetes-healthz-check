<?php
namespace SymfonyKubernetesHealthzCheck\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_kubernetes_healthz_check');

        $root = method_exists(TreeBuilder::class, 'getRootNode')
            ? $treeBuilder->getRootNode()
            : $treeBuilder->root('symfony_kubernetes_healthz_check');

        $root
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