<?php
namespace MCEikens\SymfonyKubernetesHealthzCheck\Checker\Healthz;

use MCEikens\SymfonyKubernetesHealthzCheck\Exception\Healthz\HealthzException;
use MCEikens\SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
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

    public function check(): bool
    {
        try {
            $doctrineConnection = $this->entityManager->getConnection();
            $doctrineConnection->executeQuery($doctrineConnection->getDatabasePlatform()->getDummySelectSQL())->free();
        } catch (Exception $e) {
            throw new HealthzException($e->getMessage());
        }

        return true;
    }
}