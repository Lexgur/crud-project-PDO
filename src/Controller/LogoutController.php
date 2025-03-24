<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;
use JetBrains\PhpStorm\NoReturn;

#[Path('/logout')]
class LogoutController extends AbstractUserController
{
    #[NoReturn]
    public function __invoke(): void
    {

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header("Location: /login");
        exit();
    }
}
