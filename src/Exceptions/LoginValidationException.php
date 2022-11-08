<?php

    namespace MuzickaProdavnica\Exceptions;

    use Exception;

    class LoginValidationException extends Exception {

        private $exceptions;

        public function __construct(string $error_message, array $exceptions)
        {
            parent::__construct($error_message);
            $this->exceptions = $exceptions;
        }

        public function get(string $name) {
            return $this->exceptions[$name];
        }

    }