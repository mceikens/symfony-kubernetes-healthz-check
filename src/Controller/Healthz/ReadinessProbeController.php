<?php

namespace SymfonyKubernetesHealthzCheck\Controller\Healthz;

use SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadinessProbeController extends AbstractController
{
    private HealthzCheckInterface $healthzCheckInterface;

    public function __construct(HealthzCheckInterface $healthzCheckInterface)
    {
        $this->healthzCheckInterface = $healthzCheckInterface;
    }

    #[Route('/healthz/ready', name: 'app_kubernetes_healthz_redinessprobe', methods: "GET")]
    public function __invoke(): JsonResponse
    {
        $result = $this->healthzCheckInterface->readiness();
        if (is_bool($result))
        {
            return new JsonResponse($result, Response::HTTP_OK);
        }

        return new JsonResponse($result, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}