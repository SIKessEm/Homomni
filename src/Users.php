<?php

class Users {
    public function __construct(protected \PDO $pdo, protected string $name = 'homomni_users') {}

    public function count(): int {
        return $this->pdo->query("SELECT COUNT(*) FROM $this->name")->fetchColumn();
    }

    public function tableExists(): bool {
        return $this->pdo->query($this->sql('show'))->rowCount() > 0;
    }

    public function createTable(): void {
        $this->pdo->exec($this->sql('create'));
    }

    public function dropTable(): void {
        $this->pdo->exec($this->sql('drop'));
    }

    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->name WHERE user_id = :id");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchObject(User::class) ?: null;
    }

    public function findByEmail(string $email): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM $this->name WHERE user_email = :email");
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchObject(User::class) ?: null;
    }

    public function sql(string $query): string {
        return sql("{$query}_{$this->name}_table");
    }
}