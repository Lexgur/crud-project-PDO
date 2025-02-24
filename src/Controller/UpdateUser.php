<?php

declare(strict_types=1);

namespace Crud\Controller;

class UpdateUser extends AbstractUserController
{
    public function __invoke(): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $userId = (int)($_GET['id']);
            $user = $this->userRepository->fetchById($userId);
            $user->setUserEmail($data['email']);
            $user->setUserPassword($data['password']);

            if ($this->userValidator->validate($user)) {
                $user = $this->userRepository->save($user);
                echo "Your user {$user->getUserEmail()} has been updated! here is a link to control your profile: <a class='upd-btn' href='/index.php?action=view_user&id={$user->getUserId()}'>View</a>'";
            } else {
                return $this->render('update_user_form.php', [
                    'error' => "Vartotojo {$user->getUserEmail()} atnaujinti nepavyko..."]);
            }
        }
        return $this->render('update_user_form.php', [
        ]);
    }

}