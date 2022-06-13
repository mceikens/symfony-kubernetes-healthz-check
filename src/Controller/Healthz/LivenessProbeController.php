<?php

namespace SymfonyKubernetesHealthzCheck\Controller\Healthz;

use SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivenessProbeController extends AbstractController
{
    private array $livenessProbes = [];

    public function addLivenessProbe(HealthzCheckInterface $healthzCheck)
    {
        $this->livenessProbes[] = $healthzCheck;
    }

    #[Route('/healthz/live', name: 'app_kubernetes_healthz_livenessprobe', methods: "GET")]
    public function __invoke(): JsonResponse
    {
        $resultHealthCheck = [];
        foreach ($this->livenessProbes as $check) {
            $resultHealthCheck[] = $check->liveness();
        }


        return new JsonResponse($result, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}