<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Exception\IncorrectEmailException;
use Crud\Exception\IncorrectPasswordException;
use Crud\Factory\UserFactory;
use Crud\Service\PasswordHasher;
use Crud\Validation\PasswordValidator;

class RegisterController extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $password = $data['password'];

            try {
                PasswordValidator::validate($password);
                $hashedPassword = PasswordHasher::hash($password);
                $data['password'] = $hashedPassword;
                $user = UserFactory::create($data);
                $this->userValidator->validate($user);
                $user = $this->userRepository->save($user);
                echo "Your user {$user->getUserEmail()} has been created! here is a link to control your profile: <a class='upd-btn' href='/index.php?action=view_user&id={$user->getUserId()}'>View</a>'";
            } catch (IncorrectPasswordException|IncorrectEmailException $e) {
                return $this->render('create_user_form.php', [
                    'error' => $e->getMessage()
                ]);
            }
        }
        return $this->render('create_user_form.php');
    }
}
