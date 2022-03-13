<?php

class SignupForm extends Form {
    public function __construct(string $path, string $verb = 'POST') {
        parent::__construct('signup', $path, $verb);
        $this->addField(new EmailField($this, placeholder: 'Entrez votre email'));
        $this->addField(new PasswordField($this, 'password', placeholder: 'Entrez votre mot de passe'));
        $this->addField(new PasswordField($this, 'repeat_password', placeholder: 'Confirmez votre mot de passe'));
    }
}