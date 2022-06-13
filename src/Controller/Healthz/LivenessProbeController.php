<?php

namespace SymfonyKubernetesHealthzCheck\Controller\Healthz;

use SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LivenessProbeController extends AbstractController
{
    private HealthzCheckInterface $healthzCheckInterface;

    public function __construct(HealthzCheckInterface $healthzCheckInterface)
    {
        $this->healthzCheckInterface = $healthzCheckInterface;
    }

    #[Route('/healthz/live', name: 'app_kubernetes_healthz_livenessprobe', methods: "GET")]
    public function __invoke(): JsonResponse
    {
        dump('test');
        die();
        $result = $this->healthzCheckInterface->liveness();
        if (is_bool($result))
        {
            return new JsonResponse($result, Response::HTTP_OK);
        }

        return new JsonResponse($result, Response::HTTP_INTERNAL_SERVER_ERROR);
    }
}