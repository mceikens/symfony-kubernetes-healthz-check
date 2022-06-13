<?php
namespace MCEikens\SymfonyKubernetesHealthzCheck\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('symfony_kubernetes_healthz_check');

        $treeBuilder->getRootNode()
                ->arrayNode('readinessprobes')
                    ->children()
                        ->scalarNode('name')->cannotBeEmpty()->end()
                    ->end()
                ->end()
                ->arrayNode('livenessprobes')
                    ->children()
                        ->scalarNode('name')->cannotBeEmpty()->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}