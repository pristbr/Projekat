<?php

    namespace MuzickaProdavnica\Exceptions;

    use Exception;

    class EmptyRowException extends Exception {
        public function __construct(string $error_message)
        {
            parent::__construct($error_message);
        }
    }