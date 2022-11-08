<?php

    namespace MuzickaProdavnica\Model;

    class AbstractModel {
        protected $dbName;

        public function __construct($dbName)
        {
            $this->dbName = $dbName;
        }
    }