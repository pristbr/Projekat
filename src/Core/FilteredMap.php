<?php

    namespace MuzickaProdavnica\Core;

use MuzickaProdavnica\Exceptions\FilteredMapParametarException;

class FilteredMap {
        private $filtriranuMapa;

        public function __construct(array $mapa)
        {
            $this->filtriranuMapa = $mapa;
        }

        public function getINT(string $imeParametra) : int {
            return (int) $this->filtriranuMapa[$imeParametra];
        }

        public function getNumber(string $imeParametra) : float {
            return (float) $this->filtriranuMapa[$imeParametra];
        }

        public function has(string $imeParametra) : bool {
            return isset($this->filtriranuMapa[$imeParametra]);
        }

        public function get(string $imeParametra) {
            if($this->has($imeParametra) === false) {
                throw new FilteredMapParametarException("['$imeParametra'] nije podesen!!!");
            }
            return $this->filtriranuMapa[$imeParametra];
        }

        public function getString(string $imeParametra, bool $filter = true) {
            $vrednost = (string) $this->filtriranuMapa[$imeParametra];
            return $filter ? addslashes($vrednost) : $vrednost;
        }

        public function emptyParametar(string $ime) {
            return empty($ime) ? true : false;
        }

    }