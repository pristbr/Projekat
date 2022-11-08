<?php

    namespace MuzickaProdavnica\Domain\Product;

    class Product {

        private $id;
        private $proizvodjac;
        private $ime;
        private $opis;
        private $cena;
        private $slika;
        private $tip;
        private $in_stock;

       

        public function getId() : int { return $this->id; }

        public function getProizvodjac() : string { return $this->proizvodjac; }

        public function getIme() : string { return $this->ime; }

        public function getOpis() : string { return $this->opis; }

        public function getCena() : int { return $this->cena; }

        public function getSlika() : string { return $this->slika; }

        public function getTip() : string { return $this->tip; }

        public function getInStock() : bool { return $this->in_stock; }

    }