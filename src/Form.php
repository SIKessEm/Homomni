<?php

class Form {
    const FIELDS = [
        'join' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Entrez votre e-mail',
                'options' => [
                    'required' => true,
                    'minlength' => 5,
                    'maxlength' => 255
                ]
            ],
        ],
        'login' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Entrez votre e-mail',
                'options' => [
                    'required' => true,
                    'minlength' => 5,
                    'maxlength' => 255
                ]
            ],
            'password' => [
                'type' => 'password',
                'placeholder' => 'Entrez votre mot de passe',
                'options' => [
                    'required' => true,
                    'minlength' => 5,
                    'maxlength' => 255
                ]
            ],
        ],
        'signup' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Entrez votre e-mail',
                'options' => [
                    'required' => true,
                    'minlength' => 5,
                    'maxlength' => 255
                ]
            ],
            'password' => [
                'type' => 'password',
                'placeholder' => 'Entrez votre mot de passe',
                'options' => [
                    'required' => true,
                    'minlength' => 5,
                    'maxlength' => 255
                ]
            ],
            'password_confirm' => [
                'type' => 'password',
                'placeholder' => 'Confirmez votre mot de passe',
                'options' => [
                    'required' => true,
                    'minlength' => 5,
                    'maxlength' => 255
                ]
            ],
        ],
    ];

    public function __construct(string $name, string $path, string  $verb = 'POST') {
        if (empty($fields = self::FIELDS[$name])) {
            throw new Exception('Form name not found');
        }

        $this->name = $name;
        $this->path = $path;

        switch ($this->verb = strtoupper($verb)) {
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

        foreach ($fields as $name => $field) {
            $this->fields[$name] = new FormField($this,
                name: $name,
                type: $field['type'],
                label: $field['label'] ?? '',
                placeholder: $field['placeholder'] ?? '',
                options: $field['options'] ?? [],
            );
        }
    }

    protected array $errors = [];
    protected array $fields = [];
    protected string $token = '';
    protected array $values = [];
    protected int $input = INPUT_POST;

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
        $render = "<form action='{$this->path}' method='{$this->verb}'>";
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

    public function getValue(string $name): ?string {
        return $this->values[$name] ?? null;
    }

    public function getError(string $name): ?string {
        return $this->errors[$name] ?? null;
    }

    public function __toString(): string {
        return $this->generate();
    }
}