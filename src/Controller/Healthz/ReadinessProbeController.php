<?php

namespace SymfonyKubernetesHealthzCheck\Controller\Healthz;

use SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadinessProbeController extends AbstractController
{
    private array $readinessProbes = [];

    public function addLivenessProbe(HealthzCheckInterface $healthzCheck)
    {
        $this->readinessProbes[] = $healthzCheck;
    }
    #[Route('/healthz/ready', name: 'app_kubernetes_healthz_redinessprobe', methods: "GET")]
    public function __invoke(): JsonResponse
    {
        $resultHealthCheck = [];
        foreach ($this->readinessProbes as $check) {
            $resultHealthCheck[] = $check->readiness();
        }

        return new JsonResponse($result, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}