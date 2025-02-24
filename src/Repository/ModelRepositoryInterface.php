<?php

declare(strict_types=1);

namespace Crud\Repository;

interface ModelRepositoryInterface
{
    public function insert(object $entity): object;

    public function fetchById(int $entityId): ?object;

    public function update(object $entity): object;

    public function delete(int $entityId): bool;
}
