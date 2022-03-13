<?php

class EmailField extends FormField {
    public function __construct(Form $form, string $name = 'email', string $label = '', string $placeholder = '', array $options = []) {
        $options['required'] = true;
        $options['minlength'] ??= 5;
        $options['maxlength'] ??= 255;
        parent::__construct($form, $name, 'email', $label, $placeholder, $options);
    }
}
