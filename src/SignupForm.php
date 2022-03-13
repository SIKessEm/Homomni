<?php

class SignupForm extends Form {
    public function __construct(string $path, string $verb = 'POST') {
        parent::__construct('signup', $path, $verb);
        $this->addField(new EmailField($this, placeholder: 'Entrez votre email'));
        $this->addField(new PasswordField($this, 'new_password', placeholder: 'Entrez votre mot de passe'));
        $this->addField(new PasswordField($this, 'confirm_password', placeholder: 'Confirmez votre mot de passe'));
    }
}