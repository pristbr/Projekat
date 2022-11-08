<?php

    namespace MuzickaProdavnica\Utilities;

use MuzickaProdavnica\Exceptions\DependencyNotFoundException;

class DependencyInjection {
        private $dependencies;

        public function setDependency(string $ime, $object) {
            $this->dependencies[$ime] = $object;
        }

        public function getDependency(string $dep) {
            if(!isset($this->dependencies[$dep])) {
                throw new DependencyNotFoundException("['$dep'] nije pronadjen!!!");
            }
            return $this->dependencies[$dep];
        }
    }