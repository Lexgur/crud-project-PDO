<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;

#[Path('/user/:id')]
class ViewUser extends AbstractUserController
{
    public function __invoke(): void
    {

        $userId = $_GET['id'] ?? null;

        $user = $this->userRepository->viewUser((int) $userId);

        if (!$user) {
            echo "User not found.";
            return;
        }

        echo $this->render('view_user.php', ['user' => $user]);
    }
}
