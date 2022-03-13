<?php

class FormField {
    public function __construct(protected Form $form, string $name, string $type, string $label = '', string $placeholder = '', array $options = []) {
        $this->setName($name);
        $this->setType($type);
        $this->setLabel($label);
        $this->setPlaceholder($placeholder);
        $this->setOptions($options);
    }

    protected string $name;

    public function getName(): string {
        return $this->name;
    }

    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    protected string $type;

    public function getType(): string {
        return $this->type;
    }

    public function setType(string $type): self {
        $this->type = $type;
        return $this;
    }

    protected string $label;

    public function getLabel(): string {
        return $this->label;
    }

    public function setLabel(string $label): self {
        $this->label = $label;
        return $this;
    }

    protected string $placeholder;

    public function getPlaceholder(): string {
        return $this->placeholder;
    }

    public function setPlaceholder(string $placeholder): self {
        $this->placeholder = $placeholder;
        return $this;
    }

    protected array $options;

    public function getOptions(): array {
        return $this->options;
    }

    public function setOptions(array $options): self {
        foreach ($options as $key => $value) {
            $this->setOption($key, $value);
        }
        return $this;
    }

    public function setOption(int|string $key, int|bool|string $value): self {
        $this->options[$key] = $value;
        return $this;
    }

    public function getOption(int|string $key): ?string {
        return $this->options[$key] ?? null;
    }

    public function generate(): string {
        $render = '<span class="form-field';
        $name = $this->getName();
        $type = $this->getType();

        $error = $this->getError($name);
        if ($error) {
            $render .= ' is-invalid';
        }
        $render .= '">';

        $label = $this->getLabel();
        $placeholder = $this->getPlaceholder();

        if ('' !== $label) {
            $render .= "<label for='{$name}Input'>{$label}</label>";
        }
        $render .= "<input type='{$type}' id='{$name}Input' name='{$name}'";
        if (!$placeholder) {
            $placeholder = 'Entrez ' . lcfirst($label);
        }
        $render .= " placeholder='{$placeholder}'";

        if ($error) {
            $render .= " title='{$error}'";
            $this->setOption('invalid', true);
        }

        foreach ($this->getOptions() as $option => $value) {
            $render .= is_bool($value) || is_int($option) ? " $option" : " $option='$value'";
        }

        if ($value = $this->getValue($name)) {
            $render .= " value='{$value}'";
        }

        $render .= '>';

        if ($error) {
            $render .= "<small class='error-message'>$error</small>";
        }
        $render .= '</span>';
        return $render;
    }

    public function getValue(): ?string {
        return $this->form->getValue($this->getName());
    }

    public function getError(): ?string {
        return $this->form->getError($this->getName());
    }

    public function hasError(): bool {
        return $this->form->hasError($this->getName());
    }

    public function validate(): bool {
        $form = $this->form;
        $name = $this->getName();
        $type = $this->getType();
        $value = $this->getValue();
        if (is_null($value) || '' === $value) {
            if ($this->getOption('required')) {
                $form->setError($name, "Le champ $name est requis");
                return false;
            }
            return true;
        }
        if (($minlength = $this->getOption('minlength')) && mb_strlen($value) < $minlength) {
            $form->setError($name, "Le champ $name doit contenir au moins $minlength caractères");
            return false;
        }
        if (($maxlength = $this->getOption('maxlength')) && mb_strlen($value) > $maxlength) {
            $form->setError($name, "Le champ $name ne doit pas contenir plus de $maxlength caractères");
            return false;
        }
        switch ($type) {
            case 'email':
                if (!filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $form->setError($name, "Le champ $name doit être une adresse email valide");
                    return false;
                }
                break;
            case 'url':
                if (!filter_var($value, FILTER_VALIDATE_URL)) {
                    $form->setError($name, "Le champ $name doit être une URL valide");
                    return false;
                }
                break;
            case 'password':
                if (!preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/', $value)) {
                    $form->setError($name, "Le champ $name doit contenir au moins 8 caractères, dont une majuscule, une minuscule, un chiffre et un caractère spécial");
                    return false;
                }
                break;
            default:
                throw new Exception('Validation type not found');
        }
        return true;
    }

    public function __toString(): string {
        return $this->generate();
    }
}