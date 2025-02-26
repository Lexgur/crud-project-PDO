<?php

declare(strict_types=1);

namespace Crud\Controller;

class LoginController extends AbstractUserController
{
    public function __invoke(string $userEmail): string
    {
        if ($this->isPostRequest()) {
            $data = $_POST;
            $existingUser = $this->userRepository->findByEmail($userEmail);

            if (!$existingUser) {
                return $this->render('create_user_form.php', [
                    'error' => "Vartotojas su el. paštu {$data['email']} nerastas."
                ]);
            }

            if ($this->userValidator->passwordExists($existingUser->getUserPassword(), $existingUser->getUserEmail())) {
                session_start();
                $_SESSION['userEmail'] = $existingUser->getUserEmail();

                return $this->render('dashboard.php', [
                    'message' => "Sveiki sugrįžę, {$existingUser->getUserEmail()}!"
                ]);
            } else {
                return $this->render('create_user_form.php', [
                    'error' => "Neteisingas slaptažodis vartotojui {$data['email']}."]);
            }
        }
        return $this->render('create_user_form.php', [
        ]);
    }
}