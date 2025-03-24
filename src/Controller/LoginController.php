<?php

declare(strict_types=1);

namespace Crud\Controller;

use Crud\Attribute\Path;
use Crud\Service\PasswordHasher;
use Crud\Service\PasswordVerifier;
use Crud\Validation\PasswordValidator;

#[Path('/login')]
class LoginController extends AbstractUserController
{
    public function __invoke(string $userEmail = ''): string
    {
        if ($this->isPostRequest()) {
            $userEmail = $_POST['email'] ?? '';
            $password = $_POST['password'];

            try {
                PasswordValidator::validate($password);
                $hashedPassword = PasswordHasher::hash($password);

                $existingUser = $this->userRepository->findByEmail($userEmail);

                PasswordVerifier::verify($password, $hashedPassword);

                session_start();
                $_SESSION['userEmail'] = $existingUser->getUserEmail();

                return $this->render('dashboard.php', [
                    'message' => "Sveiki sugrįžę, {$existingUser->getUserEmail()}!"
                ]);
            } catch (\Throwable $throwable) {
                return $this->render('create_user_form.php', [
                    'error' => $throwable->getMessage()
                ]);
            }
        }

        return $this->render('login_user_form.php', []);
    }
}
