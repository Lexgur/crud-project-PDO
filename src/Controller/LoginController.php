<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Service\PasswordVerifier;

class LoginController extends AbstractUserController
{
    public function __invoke(string $userEmail = ''): string
    {
        if ($this->isPostRequest()) {
            $userEmail = $_POST['email'] ?? '';
            $password = $_POST['password'];

            if (empty($userEmail) || empty($password)) {
                return $this->render('login_user_form.php', [
                    'error' => "El. pašto adresas ir slaptazodis privalomas prisijungimui."
                ]);
            }

            $existingUser = $this->userRepository->findByEmail($userEmail);

            if (!$existingUser) {
                return $this->render('create_user_form.php', [
                    'error' => "Vartotojas su el. paštu {$userEmail} nerastas."
                ]);
            }

            if (PasswordVerifier::verify($password, $existingUser->getPassword())) {
                session_start();
                $_SESSION['userEmail'] = $existingUser->getUserEmail();

                return $this->render('dashboard.php', [
                    'message' => "Sveiki sugrįžę, {$existingUser->getUserEmail()}!"
                ]);
            } else {
                return $this->render('create_user_form.php', [
                    'error' => "Neteisingas slaptažodis vartotojui {$userEmail}."
                ]);
            }
        }

        return $this->render('login_user_form.php', []);
    }
}
