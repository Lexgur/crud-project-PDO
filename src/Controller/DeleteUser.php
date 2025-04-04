<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;

#[Path('/user/:id/delete')]
class DeleteUser extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $userId = (int)($_GET['id']);

            try {
                $this->userRepository->fetchById($userId);
                $this->userRepository->delete($userId);

                echo "User {$userId} has been deleted! here is a link to create a new student: <button class='add-btn'><a href='/user/create'>CREATE</a></button>'";
            } catch (\Throwable $throwable) {
                return $this->render('delete_user_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }
        return $this->render('delete_user_form.php');
    }
}
