<?php

declare(strict_types=1);

namespace Crud\Controller;

class DeleteUser extends AbstractUserController
{

    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $userId = (int)($_GET['id']);
            $user = $this->userRepository->fetchById($userId);

            if ($user) {
                $this->userRepository->delete($userId);
                echo "User {$userId} has been deleted! here is a link to create a new student: <button class='add-btn'><a href='/index.php?action=create_user'>CREATE</a></button>'";

            } else {
                return $this->render('delete_user_form.php', [
                    'error' => "Student not found, deletion failed"
                ]);
            }
        }
        return $this->render('delete_user_form.php');
    }
}
