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
            ->children()
                ->arrayNode('readinessprobes')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
                ->arrayNode('livenessprobes')
                    ->prototype('array')
                        ->children()
                            ->scalarNode('id')->cannotBeEmpty()->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;

        return $treeBuilder;
    }
}