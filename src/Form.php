<?php

class Form {
    public function __construct(string $name, string $path, string  $verb = 'POST') {
        $this->name = $name;
        $this->path = strtolower($path);
        $this->verb = strtoupper($verb);
        switch ($this->verb) {
            case 'GET':
                $this->values = $_GET;
                $this->input = INPUT_GET;
                break;
            case 'POST':
                $this->values = $_POST;
                $this->input = INPUT_POST;
                break;
            default:
                throw new Exception('Verb not found');
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }

    protected array $errors = [];
    protected array $fields = [];
    protected string $token = '';
    protected array $values = [];
    protected int $input = INPUT_POST;

    public function addField(FormField $field) {
        $this->fields[] = $field;
    }

    public function getFields() {
        return $this->fields;
    }

    public function getField(string $name): ?FormField {
        foreach ($this->fields as $field) {
            if ($name === $field->getName()) {
                return $field;
            }
        }
        return null;
    }

    public function validate(string $name, string $path, string $verb): bool {
        if (!$this->validateRequest($path, $verb)) {
            return false;
        }
        if (!$this->validateAction($name)) {
            return false;
        }
        if (!$this->validateToken()) {
            return false;
        }
        if (!$this->validateFields()) {
            return false;
        }
        return true;
    }

    protected function validateRequest(string $path, string $verb): bool {
        if (strtolower($this->verb) !== strtolower($verb)) {
            $this->errors[] = 'Invalid request verb';
            return false;
        }
        if (strtoupper($this->path) !== strtoupper($path)) {
            $this->errors[] = 'Invalid request path';
            return false;
        }
        return true;
    }

    public function validateAction(string $name): bool {
        if ($this->name !== $name) {
            $this->errors[] = 'Invalid action';
            return false;
        }
        return true;
    }

    public function validateToken(): bool {
        if (!isset($_POST['token']) || !isset($_SESSION["token_{$this->name}"]) || $_POST['token'] !== $_SESSION["token_{$this->name}"]) {
            $this->errors['token'] = 'Invalid token';
            return false;
        }
        return true;
    }

    public function generateToken(): string {
        return $this->token = $_SESSION["token_{$this->name}"] = bin2hex(random_bytes(32)) . uniqid(rand(), true);
    }

    public function validateFields(): array {
        $fields = [];
        foreach ($this->fields as $field) {
            if ($field->validate()) {
                $fields[$field->getName()] = $field->getValue();
            }
        }
        return $fields;
    }

    public function generate(): string {
        $render = "<form action='{$this->path}' method='{$this->verb}'" . ($this->hasErrors() ? ' class="has-errors"' : '') . '>';
        $render .= $this->generateFields();
        $render .= "<input type='hidden' name='token' value='{$this->generateToken()}'/>";
        $render .= "<button type='submit' name='action' value='{$this->name}'>Envoyer</button>";
        $render .= "</form>";
        return $render;
    }

    public function generateFields(): string {
        $render = '';
        foreach ($this->fields as $field) {
            $render .= $field->generate();
        }
        return $render;
    }

    public function setValues(array $values): self {
        foreach ($values as $name => $value) {
            $this->setValue($name, $value);
        }
        return $this;
    }

    public function setValue(string $name, mixed $value): self {
        $this->values[$name] = $value;
        return $this;
    }

    public function getValues(): array {
        return $this->values;
    }

    public function hasValue(string $name): bool {
        return isset($this->values[$name]);
    }

    public function getValue(string $name): ?string {
        return $this->values[$name] ?? null;
    }

    public function setErrors(array $errors): self {
        foreach ($errors as $name => $error) {
            $this->setError($name, $error);
        }
        return $this;
    }

    public function setError(string $name, string $error): self {
        $this->errors[$name] = $error;
        return $this;
    }

    public function hasErrors(): bool {
        return !empty($this->errors);
    }

    public function getErrors(): array {
        return $this->errors;
    }

    public function getError(string $name): ?string {
        return $this->errors[$name] ?? null;
    }

    public function hasError(string $name): bool {
        return isset($this->errors[$name]);
    }

    public function __toString(): string {
        return $this->generate();
    }
}