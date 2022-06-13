<?php

namespace SymfonyKubernetesHealthzCheck\Interface\Healthz;

interface HealthzCheckInterface
{
    public function liveness();
    public function readiness();
}