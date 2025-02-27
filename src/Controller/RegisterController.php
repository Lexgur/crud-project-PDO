<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\UserFactory;
use Crud\Service\PasswordHasher;

class RegisterController extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $data['password'] = PasswordHasher::hash($data['password']);
            $user  = UserFactory::create($data);

            if ($this->userValidator->validate($user)) {
                $user = $this->userRepository->save($user);
                echo "Your user {$user->getUserEmail()} has been created! here is a link to control your profile: <a class='upd-btn' href='/index.php?action=view_user&id={$user->getUserId()}'>View</a>'";
            } else {
                return $this->render('create_user_form.php', [
                    'error' => "Vartotojo {$user->getUserEmail()} sukurti nepavyko..."]);
            }
        }
        return $this->render('create_user_form.php', [
        ]);
    }

}