<?php

class Form {
    const FIELDS = [
        'join' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Entrez votre e-mail',
                'required' => true,
                'minlength' => 5,
                'maxlength' => 255,
            ],
        ],
        'login' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Entrez votre e-mail',
                'required' => true,
                'minlength' => 5,
                'maxlength' => 255,
            ],
            'password' => [
                'type' => 'password',
                'placeholder' => 'Entrez votre mot de passe',
                'required' => true,
                'minlength' => 5,
                'maxlength' => 255,
            ],
        ],
        'signup' => [
            'email' => [
                'type' => 'email',
                'placeholder' => 'Entrez votre e-mail',
                'required' => true,
                'minlength' => 5,
                'maxlength' => 255,
            ],
            'password' => [
                'type' => 'password',
                'placeholder' => 'Entrez votre mot de passe',
                'required' => true,
                'minlength' => 5,
                'maxlength' => 255,
            ],
            'password_confirm' => [
                'type' => 'password',
                'placeholder' => 'Confirmez votre mot de passe',
                'required' => true,
                'minlength' => 5,
                'maxlength' => 255,
            ],
        ],
    ];

    public function __construct(string $name, string $path, string  $verb = 'POST') {
        if (!isset(self::FIELDS[$name])) {
            throw new Exception('Form name not found');
        }
        $this->fields = self::FIELDS[$name];
        $this->name = $name;

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
        $this->path = $path;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
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
        foreach ($this->fields as $name => $field) {
            if ($field = $this->validateField($name, $field)) {
                $fields[$name] = $field;
            }
        }
        return $fields;
    }

    public function validateField(string $name, array $field): ?array {
        if (!isset($this->values[$name])) {
            if (isset($field['required']) && $field['required']) {
                $this->errors[$name] = "Le champ $name est requis";
                return null;
            }
            return $field;
        }
        $value = $this->values[$name];
        if (isset($field['minlength']) && mb_strlen($value) < $field['minlength']) {
            $this->errors[$name] = "Le champ $name doit contenir au moins {$field['minlength']} caractères";
            return null;
        }
        if (isset($field['maxlength']) && mb_strlen($value) > $field['maxlength']) {
            $this->errors[$name] = "Le champ $name doit contenir au plus {$field['maxlength']} caractères";
            return null;
        }
        switch ($field['type']) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->errors[$name] = $validation['message'] ?? "Le champ $name doit être une adresse e-mail valide";
                    return null;
                }
                break;
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $this->errors[$name] = $validation['message'] ?? "Le champ $name doit être une URL valide";
                    return null;
                }
                break;
            case 'password':
                if (empty($value)) {
                    $this->errors[$name] = $validation['message'] ?? "Le champ $name doit être un mot de passe";
                    return null;
                }
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value)) {
                    $this->errors[$name] = $validation['message'] ?? "Le champ $name doit contenir au moins 8 caractères, une majuscule, une minuscule, un chiffre et un caractère spécial";
                    return null;
                }
                break;
            default:
                throw new Exception('Validation type not found');
        }
        return $field;
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
        foreach ($this->fields as $name => $field) {
            $render .= $this->generateField(
                name: $name,
                type: $field['type'] ?? 'text',
                label: $field['label'] ?? '',
                required: $field['required'] ?? false,
                placeholder: $field['placeholder'] ?? '',
            );
        }
        return $render;
    }

    public function generateField(string $name, string $type, string $label, bool $required = false, string $placeholder = ''): string {
        $render = '';
        if ('' !== $label) {
            $render .= "<label for='{$name}'>{$label}</label>";
        }
        $render .= "<input type='{$type}' name='{$name}'";
        if (!$placeholder) {
            $placeholder = 'Entrez ' . lcfirst($label);
        }
        $render .= " placeholder='{$placeholder}'";
        if ($required) {
            $render .= ' required';
        }
        if ($value = $this->getValue($name)) {
            $render .= " value='{$value}'";
        }
        if ($error = $this->getError($name)) {
            $render .= " class='error'";
            $render .= " title='{$error}'";
        }
        $render .= '>';
        return $render;
    }

    public function getValue(string $name): ?string {
        switch ($this->verb) {
            case 'POST':
                return $_POST[$name] ?? null;
            case 'GET':
                return $_GET[$name] ?? null;
        }
        return null;
    }

    public function getError(string $name): ?string {
        return $this->errors[$name] ?? null;
    }

    public function __toString(): string {
        return $this->generate();
    }
}