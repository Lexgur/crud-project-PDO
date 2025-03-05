<?php

declare(strict_types=1);

namespace Crud\DependencyInjection;

/**
 * Interface for services managed by the Container.
 */
interface ServiceInterface
{
    /**
     * Checks if the service is initialized.
     *
     * @return bool
     */
    public function isInitialized(): bool;
}