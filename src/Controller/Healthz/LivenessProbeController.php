<?php

namespace MCEikens\SymfonyKubernetesHealthzCheck\Controller\Healthz;

use MCEikens\SymfonyKubernetesHealthzCheck\Exception\Healthz\HealthzException;
use MCEikens\SymfonyKubernetesHealthzCheck\Interface\Healthz\HealthzCheckInterface;
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

    public function __invoke(): JsonResponse
    {
        $result = [];
        $result['liveness'] = true;
        $statusCode = Response::HTTP_OK;

        foreach ($this->livenessProbes as $check)
        {
            try {
                $check->check();

                if ($statusCode !== Response::HTTP_INTERNAL_SERVER_ERROR)
                {
                    $result['liveness'] = true;
                    $statusCode = Response::HTTP_OK;
                }

            } catch (HealthzException $exception) {
                $result[] = ['message' => $exception->getMessage()];
                $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR;
                $result['liveness'] = false;
            }
        }

        return new JsonResponse($result, $statusCode);
    }
}