<?php

class UsersManager extends Manager {
    public function __construct(protected \PDO $pdo) {
        parent::__construct($pdo, 'homomni_users', UserEntity::class);
    }

    public function findById(int $id): ?UserEntity {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject($this->getClassName()) ?: null;
    }

    public function findByEmail(string $email): ?UserEntity {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_email = :email");
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject($this->getClassName()) ?: null;
    }

    public function add(UserEntity $user): int {
        $stmt = $this->pdo->prepare("INSERT INTO {$this->getTableName()} (user_email, user_password, user_created_at, user_updated_at) VALUES (:email, :password, NOW(), NOW())");
        $stmt->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $stmt->bindValue(':password', $user->getPassword(), \PDO::PARAM_STR);
        $stmt->execute();
        $id = $this->pdo->lastInsertId();
        $user->setId($id);
        return $id;
    }
}