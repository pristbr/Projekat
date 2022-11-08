<?php

    namespace MuzickaProdavnica\Utilities;

    trait Unique {

        protected $user_id;

        public function setID(int $id) : void {
            $this->user_id = $id;
        }

        public function getID() : int {
            return $this->user_id;
        }


    }