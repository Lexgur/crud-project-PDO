<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;
use Crud\Service\PasswordHasher;
use Crud\Validation\PasswordValidator;

#[Path('/user/:id/edit')]
class UpdateUser extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $password = $data['password'];
            $email = $data['email'];

            try {
                $userId = (int)($_GET['id']);
                $user = $this->userRepository->fetchById($userId);

                $user->setUserEmail($email);

                PasswordValidator::validate($password);
                $this->userValidator->validate($user);
                $hashedPassword = PasswordHasher::hash($password);
                $user->setUserPassword($hashedPassword);

                $user = $this->userRepository->save($user);

                echo "Your user {$user->getUserEmail()} has been updated! Here is a link to control your profile: <a class='upd-btn' href='/user/{$user->getUserId()}/edit'>View</a>";
            } catch (\Throwable $throwable) {
                return $this->render('update_user_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }
        return $this->render('update_user_form.php');
    }
}
