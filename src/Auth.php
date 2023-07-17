<?php

namespace App;

use PDO;

class Auth {

    private $pdo;
    private $loginPath;

    public function __construct(PDO $pdo, string $loginPath = null)
    {
        $this->pdo = $pdo;
        $this->loginPath = $loginPath;
    }

    // Find the currently authenticated user based on session data
    public function user(): ?User
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $email = $_SESSION['auth_email'] ?? null;
        if ($email === null) {
            return null;
        }
        $query = $this->pdo->prepare('SELECT * FROM users WHERE email = ?');
        $query->execute([$email]);
        $user = $query->fetchObject(User::class);
        return $user ?: null;
    }

    // Perform user login with email and password
    public function login(string $email, string $password): ?User
    {
        $query = $this->pdo->prepare('SELECT * FROM users WHERE email = :email');
        $query->execute(['email' => $email]);
        $user = $query->fetchObject(User::class);

        if ($user === false) {
            return null;
        }

        if (password_verify($password, $user->password)) {
            if ($user->isValid === 0) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                return $user;
            }
            if ($user->isValid === 1) {
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                $_SESSION['auth_email'] = $user->email;
                $token = uniqid();
                $_SESSION['auth_token'] = $token;
                return $user;
            }
        }
        return null;
    }

    // Handle the sign-in process, including form validation, user authentication, and session management
    public function signin(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (empty($_POST['email']) || empty($_POST['password'])) {
                FlashMessage::setFlashMessage("Au moins l'un des champs est vide.", 'danger');
            } else {
                $user = $this->login($_POST['email'], $_POST['password']);
                if (!$user) {
                    FlashMessage::setFlashMessage("Identifiants incorrects.", 'danger');
                } elseif ($user && $user->isValid === 0) {
                    FlashMessage::setFlashMessage("Votre adresse email n'est pas encore validée.", 'info');
                    header('Location: login');
                    exit();
                } else {
                    if (isset($_POST['rememberMe'])) {
                        // Generate an authentication token with a validity period of one month in seconds if 'rememberMe' is checked
                        // and store it in the session
                        $expiration = time() + (30 * 24 * 60 * 60);
                        $_SESSION['auth_expiration'] = $expiration;
                    } else {
                        // Generate an authentication token with a validity period of one hour
                        $expiration = time() + (60 * 60);
                        $_SESSION['auth_expiration'] = $expiration;
                    }
                    FlashMessage::setFlashMessage('Vous êtes bien connecté !', 'success');
                    $referer = $_POST['referer'];
                    if ($user->role === 'admin') {
                        header("Location: /users-list");
                    } else {
                        header("Location: $referer");
                    }
                    exit();
                }
            }
        }
    }

    // Check if the user is already signed in.
    public function alreadySignin(): void
    {
        if (!empty($_SESSION['auth_token'])){
            FlashMessage::setFlashMessage("Vous êtes déjà connecté.", 'info');
            header("Location: /");
            exit();
        }
    }

    // Check if the user's session has expired.
    public function sessionTimeout(): void
    {
        if (isset($_SESSION['auth_token']) && isset($_SESSION['auth_expiration'])) {
            $expiration = $_SESSION['auth_expiration'];
            if (time() > $expiration) {
                if (session_status() === PHP_SESSION_ACTIVE) {
                    session_destroy();
                }
                if (session_status() === PHP_SESSION_NONE) {
                    session_start();
                }
                FlashMessage::setFlashMessage("Veuillez vous reconnecter.", 'warning');
                header("Location: /login");
                exit();
            }
        }
    }

    // Check if the authenticated user has the required role(s).
    public function requireRole(string ...$roles): void
    {
        $user = $this->user();
        $this->loginPath === '/';
        if ($user === null || !in_array($user->role, $roles)) {
            header("Location: /403");
            exit();
        }
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_destroy();
        }
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        FlashMessage::setFlashMessage('Vous êtes bien déconnecté !', 'success');
        header('Location: /login');
        exit();
    }
}