<?php

class UsersManager extends Manager {
    public function __construct(protected \PDO $pdo) {
        parent::__construct($pdo, 'homomni_users', UserEntity::class);
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject($this->getClassName()) ?: null;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE user_email = :email");
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject($this->getClassName()) ?: null;
    }
}