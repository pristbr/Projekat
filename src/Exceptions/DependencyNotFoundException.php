<?php

    namespace MuzickaProdavnica\Exceptions;

    use Exception;

    class DependencyNotFoundException extends Exception {
        public function __construct(string $error_message)
        {
            parent::__construct($error_message);
        }
    }