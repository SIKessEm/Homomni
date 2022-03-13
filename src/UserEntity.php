<?php

class UserEntity extends Entity {
    protected int $user_id;
    protected string $user_email;
    protected string $user_password;
    protected string $user_created_at;
    protected string $user_updated_at;

    public function __construct(array $data = []) {
        parent::__construct($data);
    }

    public function getId(): int {
        return $this->user_id;
    }

    public function setId(int $id): void {
        $this->user_id = $id;
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

    public function getCreatedAt(): string {
        return $this->user_created_at;
    }

    public function setCreatedAt(string $created_at): void {
        $this->user_created_at = $created_at;
    }

    public function getUpdatedAt(): string {
        return $this->user_updated_at;
    }

    public function setUpdatedAt(string $updated_at): void {
        $this->user_updated_at = $updated_at;
    }
}