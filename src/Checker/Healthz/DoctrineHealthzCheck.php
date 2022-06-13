<?php
namespace SymfonyKubernetesHealthzCheck\Checker\Healthz;

use SymfonyKubernetesHealthzCheck\Exception\Healthz\HealthzException;
use SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class DoctrineHealthzCheck implements HealthzCheckInterface
{
    private ContainerInterface $container;
    private EntityManagerInterface $entityManager;

    public function __construct(
        ContainerInterface $container,
        EntityManagerInterface $entityManager
    ) {
        $this->container = $container;
        $this->entityManager = $entityManager;
    }

    /**
     * @return bool
     * @throws HealthzException
     */
    public function liveness(): bool
    {
        if ($this->container->get('symfony_kubernetes_health_checks.database.livenessprobe'))
        {
            return $this->check();
        }

        return false;
    }

    /**
     * @return bool
     * @throws HealthzException
     */
    public function readiness(): bool
    {
        if ($this->container->get('symfony_kubernetes_health_checks.database.readinessprobe'))
        {
            return $this->check();
        }

        return false;
    }

    /**
     * @return bool
     * @throws HealthzException
     */
    private function check(): bool
    {
        if ($this->container->get('symfony_kubernetes_health_checks.database.enabled'))
        {
            try {
                $doctrineConnection = $this->entityManager->getConnection();
                $doctrineConnection->executeQuery($doctrineConnection->getDatabasePlatform()->getDummySelectSQL())->free();
            } catch (Exception $e) {
                throw new HealthzException($e->getMessage());
            }

            return true;
        }

        return false;
    }
}