<?php

    namespace MuzickaProdavnica\Core;

use MuzickaProdavnica\Exceptions\KeyNotFoundException;

class Config {
        private $data;

        public function __construct()
        {
            $json_file = file_get_contents(__DIR__ . '/../../config/app.json');
            $this->data = json_decode($json_file, true);
        }


        public function getConfigFile(string $kljuc) {
            if(!isset($this->data[$kljuc])) {
                throw new KeyNotFoundException("['$kljuc'] nije nadjen!!!");
            }
            return $this->data[$kljuc];
        }
    }