<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;
use Crud\Factory\UserFactory;
use Crud\Service\PasswordHasher;
use Crud\Validation\PasswordValidator;
#[Path('/user/create')]
class CreateUser extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $password = $data['password'];

            try {

                PasswordValidator::validate($password);

                $user = UserFactory::create($data);
                $this->userValidator->validate($user);

                $userPassword = $user->getUserPassword();
                $hashedPassword = PasswordHasher::hash($userPassword);
                $user->setUserPassword($hashedPassword);

                $user = $this->userRepository->save($user);

                echo "Your user {$user->getUserEmail()} has been created! here is a link to control your profile: <a class='upd-btn' href='/user/{$user->getUserId()}/edit'>View</a>'";

            } catch (\Throwable $throwable) {
                return $this->render('create_user_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }
        return $this->render('create_user_form.php');
    }
}