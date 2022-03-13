<?php

class Users {
    public function __construct(protected \PDO $pdo) {}

    public function count(): int {
        return $this->pdo->query('SELECT COUNT(*) FROM users')->fetchColumn();
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

    public function sql(string $name): string {
        return sql("{$name}_users_table");
    }
}