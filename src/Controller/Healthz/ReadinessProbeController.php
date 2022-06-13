<?php

namespace MCEikens\SymfonyKubernetesHealthzCheck\Controller\Healthz;

use MCEikens\SymfonyKubernetesHealthzCheck\Exception\Healthz\HealthzException;
use MCEikens\SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReadinessProbeController extends AbstractController
{
    private array $readinessProbes = [];

    public function addReadinessProbe(HealthzCheckInterface $healthzCheck)
    {
        $this->readinessProbes[] = $healthzCheck;
    }

    public function __invoke(): JsonResponse
    {
        $result = [];
        $result['readiness'] = true;
        $statusCode = Response::HTTP_OK;

        foreach ($this->readinessProbes as $check)
        {
            try {
                $check->check();

                if ($statusCode !== Response::HTTP_INTERNAL_SERVER_ERROR)
                {
                    $result['readiness'] = true;
                    $statusCode = Response::HTTP_OK;
                }

            } catch (HealthzException $exception) {
                $result[] = ['message' => $exception->getMessage()];
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $result['readiness'] = false;
            }
        }

        return new JsonResponse($result, $statusCode);
    }
}