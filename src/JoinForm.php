<?php

class JoinForm extends Form {
    public function __construct(string $path, string $verb = 'POST') {
        parent::__construct('join', $path, $verb);
        $this->addField(new EmailField($this, placeholder: 'Entrez votre email'));
    }
}
