<?php

namespace MCEikens\SymfonyKubernetesHealthzCheck\Interface\Healthz;

interface HealthzCheckInterface
{
    public function check();
}