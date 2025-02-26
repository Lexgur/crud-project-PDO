<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Factory\UserFactory;

class LoginController extends AbstractUserController
{
    public function __invoke(string $userEmail): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $user  = UserFactory::create($data);
            $existingUser = $this->userRepository->findByEmail($userEmail);

            if ($existingUser !== null && $existingUser->getUserPassword() == $data['password']) {
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
}