<?php

class GenericUserDAO extends GenericObjectDAO {
    /**
     * Authentication of a login
     * @param string $login Username or E-Mail
     * @param bool $loginWithEmail Login performed with E-Mail instead of username
     * @param string $password Provided password
     * @return GenericUser|int User or error code if login failed
     *                         0: Login not found
     *                         1: Password incorrect
     *                         2: E-Mail not verified
     */
    public function login(string $login, bool $loginWithEmail, string $password): GenericUser|int {
        if($loginWithEmail) {
            $login = strtolower($login);
            $user = $this->getObject([
                "email" => $login
            ]);
        } else {
            $user = $this->getObject([
                "username" => $login
            ]);
        }

        if($user instanceof GenericUser) {
            if(password_verify($password, $user->getPassword())) {
                if(!$user->getEmailVerified()) {
                    return 2;
                }

                return $user;
            }
        } else {
            return 0;
        }

        return 1;
    }

    /**
     * Registers a new user
     * @param string $username Username
     * @param string $password Password
     * @param string $email E-Mail
     * @param int $permissionLevel Permission level
     * @param string $oneTimePassword One-time-password for E-Mail verification
     * @return User Newly created user
     */
    public function register(string $username, string $password, string $email, int $permissionLevel, string $oneTimePassword): User {
        $user = new User();
        $user->setUsername($username);
        $user->setPassword($password);
        $user->setEmail($email);
        $user->setEmailVerified(false);
        $user->setPermissionLevel($permissionLevel);
        $user->setOneTimePassword($oneTimePassword);
        $user->setOneTimePasswordExpiration(null);
        $this->save($user);

        return $user;
    }

    /**
     * Returns a unique one-time-password
     * @return string
     */
    public function generateOneTimePassword(): string {
        $chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $oneTimePassword = "";
        for($i = 0; $i < 127; $i++) {
            $oneTimePassword .= $chars[rand(0, strlen($chars) - 1)];
        }

        // Check whether the generated one-time-password already exists
        if(sizeof($this->getObjects(["oneTimePassword" => $oneTimePassword])) > 0) {
            $oneTimePassword = $this->generateOneTimePassword();
        }

        return $oneTimePassword;
    }
}
