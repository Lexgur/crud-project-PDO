<?php

declare(strict_types=1);

namespace Crud\Controller;

use JetBrains\PhpStorm\NoReturn;

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

        header("Location: /index.php?action=login_user");
        exit();
    }
}