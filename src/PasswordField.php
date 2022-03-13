<?php

class PasswordField extends FormField {
    public function __construct(Form $form, string $name = 'password', string $label = '', string $placeholder = '', array $options = []) {
        $options['required'] = true;
        $options['minlength'] = $options['minlength'] ??= 8;
        $options['maxlength'] = $options['maxlength'] ??= 255;
        parent::__construct($form, $name, 'password', $label, $placeholder, $options);
    }
}
