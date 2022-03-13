<?php

class User {
    protected int $user_id;
    protected string $user_name;
    protected string $user_email;
    protected string $user_password;
    protected DateTime $user_created_at;
    protected DateTime $user_updated_at;

    public function __construct(int $id, string $name, string $email, string $password, DateTime $created_at, DateTime $updated_at) {
        $this->setId($id);
        $this->setName($name);
        $this->setEmail($email);
        $this->setPassword($password);
        $this->setCreatedAt($created_at);
        $this->setUpdatedAt($updated_at);
    }

    public function getId(): int {
        return $this->user_id;
    }

    public function setId(int $id): void {
        $this->user_id = $id;
    }

    public function getName(): string {
        return $this->user_name;
    }

    public function setName(string $name): void {
        $this->user_name = $name;
    }

    public function getEmail(): string {
        return $this->user_email;
    }

    public function setEmail(string $email): void {
        $this->user_email = $email;
    }

    public function getPassword(): string {
        return $this->user_password;
    }

    public function setPassword(string $password): void {
        $this->user_password = $password;
    }

    public function getCreatedAt(): DateTime {
        return $this->user_created_at;
    }

    public function setCreatedAt(DateTime $created_at): void {
        $this->user_created_at = $created_at;
    }

    public function getUpdatedAt(): DateTime {
        return $this->user_updated_at;
    }

    public function setUpdatedAt(DateTime $updated_at): void {
        $this->user_updated_at = $updated_at;
    }
}