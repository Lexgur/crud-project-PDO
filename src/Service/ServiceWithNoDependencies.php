<?php

namespace Crud\Service;

class ServiceWithNoDependencies
{
    public function __construct()
    {
    }

    public function isInitialized(): bool
    {
        return true;
    }
}
