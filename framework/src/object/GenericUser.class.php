<?php

class GenericUser extends GenericObject {
    public string $username = "";
    public string $password = "";
    public string $email = "";
    public bool $emailVerified = false;
    public int $permissionLevel = 0;
    public ?string $oneTimePassword = null;
    public ?string $oneTimePasswordExpiration = null;

    /**
     * Returns the user's username
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * Sets the user's username
     * @param string $username
     */
    public function setUsername(string $username): void {
        $this->username = $username;
    }

    /**
     * Returns the user's password hash
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Sets the user's password
     * The passed password will be hashed with the default PHP hashing algorithm
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->password = password_hash($password, PASSWORD_DEFAULT);
    }

    /**
     * Returns the user's E-Mail
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Sets the user's E-Mail
     * The E-Mail will be converted to lowercase letters
     * @param string $email
     */
    public function setEmail(string $email): void {
        $this->email = strtolower($email);
    }

    /**
     * Returns the user's E-Mail verification status
     * @return bool
     */
    public function getEmailVerified(): bool {
        return $this->emailVerified;
    }

    /**
     * Sets the user's E-Mail verification status
     * @param bool $emailVerified
     */
    public function setEmailVerified(bool $emailVerified): void {
        $this->emailVerified = $emailVerified;
    }

    /**
     * Returns the user's permission level
     * @return int
     */
    public function getPermissionLevel(): int {
        return $this->permissionLevel;
    }

    /**
     * Sets the user's permission level
     * @param int $permissionLevel
     */
    public function setPermissionLevel(int $permissionLevel): void {
        $this->permissionLevel = $permissionLevel;
    }

    /**
     * Returns the user's one-time-password
     * @return string|null
     */
    public function getOneTimePassword(): ?string {
        return $this->oneTimePassword;
    }

    /**
     * Sets the user's one-time-password
     * The one-time-password will be hashed with the default PHP hashing algorithm
     * @param string|null $oneTimePassword
     */
    public function setOneTimePassword(?string $oneTimePassword): void {
        if($oneTimePassword !== null) {
            $this->oneTimePassword = password_hash($oneTimePassword, PASSWORD_DEFAULT);
        } else {
            $this->oneTimePassword = null;
        }
    }

    /**
     * Returns the user's one-time-password expiration date
     * @return string|null
     */
    public function getOneTimePasswordExpiration(): ?string {
        return $this->oneTimePasswordExpiration;
    }

    /**
     * Sets the user's one-time-password expiration date
     * @param string|null $oneTimePasswordExpiration
     */
    public function setOneTimePasswordExpiration(?string $oneTimePasswordExpiration): void {
        $this->oneTimePasswordExpiration = $oneTimePasswordExpiration;
    }
}
