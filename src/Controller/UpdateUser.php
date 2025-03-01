<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectIdException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Service\PasswordHasher;
use Crud\Validation\PasswordValidator;

class UpdateUser extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $password = $data['password'];

            try {
                PasswordValidator::validate($password);
                PasswordHasher::hash($password);
                $userId = (int)($_GET['id']);
                $user = $this->userRepository->fetchById($userId);
                $user->setUserEmail($data['email']);
                $user->setUserPassword($password);
                $this->userValidator->validate($user);
                $user = $this->userRepository->save($user);
                echo "Your user {$user->getUserEmail()} has been updated! here is a link to control your profile: <a class='upd-btn' href='/index.php?action=view_user&id={$user->getUserId()}'>View</a>'";
            } catch (IncorrectPasswordException|IncorrectEmailException|IncorrectIdException $e) {
                return $this->render('update_user_form.php', [
                    'error' => $e->getMessage()
                ]);
            }
        }
        return $this->render('update_user_form.php');
    }
}