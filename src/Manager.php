<?php

abstract class Manager {
    protected \PDO $pdo;

    public function __construct(\PDO $db, string $tableName, string $className) {
        $this->pdo = $db;
        $this->setTableName($tableName);
        $this->setClassName($className);
    }

    protected string $tableName;

    public function setTableName(string $tableName) {
        $this->tableName = $tableName;
    }

    public function getTableName() {
        return $this->tableName;
    }

    protected string $className;

    public function setClassName(string $className) {
        if (!class_exists($className) || !is_subclass_of($className, 'Entity')) {
            throw new \Exception("Class $className does not exist");
        }
        $this->className = $className;
    }

    public function getClassName() {
        return $this->className;
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

    public function find(int $id): ?Entity {
        $query = $this->pdo->prepare("SELECT * FROM {$this->getTableName()} WHERE id = :id");
        $query->execute(['id' => $id]);
        return $query->fetchObject($this->getClassName());
    }

    public function findAll(): array {
        $query = $this->pdo->query("SELECT * FROM {$this->getTableName()}");
        return $query->fetchAll(PDO::FETCH_CLASS, $this->getClassName());
    }

    public function count(): int {
        return $this->pdo->query("SELECT COUNT(*) FROM {$this->getTableName()}")->fetchColumn();
    }

    public function sql(string $query): string {
        return sql("{$query}_{$this->getTableName()}_table");
    }
}