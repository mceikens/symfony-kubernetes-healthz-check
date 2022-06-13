<?php

namespace MCEikens\SymfonyKubernetesHealthzCheck\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\Loader\XmlFileLoader;
use MCEikens\SymfonyKubernetesHealthzCheck\Checker\Healthz\DoctrineHealthzCheck;
use MCEikens\SymfonyKubernetesHealthzCheck\Controller\Healthz\LivenessProbeController;
use MCEikens\SymfonyKubernetesHealthzCheck\Controller\Healthz\ReadinessProbeController;

class SymfonyKubernetesHealthzCheckExtension extends Extension
{

    /**
     * @throws \Exception
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new XmlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('controller.xml');

        $this->loadChecks($config, $loader, $container);
    }

    /**
     * @param array<array> $config
     */
    private function loadChecks(
        array $config,
        XmlFileLoader $loader,
        ContainerBuilder $container
    ): void {
        $loader->load('checks.xml');

        $readinessCheckCollection = $container->findDefinition(ReadinessProbeController::class);

        foreach ($config['readinessprobes'] as $readinessprobeConfig) {
            $definition = new Reference($readinessprobeConfig['name']);
            $readinessCheckCollection->addMethodCall('addReadinessProbe', [$definition]);
        }

        $livenessCheckCollection = $container->findDefinition(LivenessProbeController::class);
        foreach ($config['livenessprobes'] as $livenessprobeConfig) {
            $definition = new Reference($livenessprobeConfig['name']);
            $livenessCheckCollection->addMethodCall('addLivenessProbe', [$definition]);
        }
    }
}