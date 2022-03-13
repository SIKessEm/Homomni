<?php

class LoginForm extends Form {
    public function __construct(string $path, string $verb = 'POST') {
        parent::__construct('login', $path, $verb);
        $this->addField(new EmailField($this, placeholder: 'Entrez votre email'));
        $this->addField(new PasswordField($this, placeholder: 'Entrez votre mot de passe'));
    }
}
