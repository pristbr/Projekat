<?php

    namespace MuzickaProdavnica\Core;

    class Request {

        const GET = "GET";
        const POST = "POST";

        private $domainName;
        private $path;
        private $method;
        private $parametri;
        private $kolacici;
        public function __construct()
        {
            $this->domainName = $_SERVER['HTTP_HOST'];
            $this->path = explode('?', $_SERVER["REQUEST_URI"])[0];
            $this->method = $_SERVER['REQUEST_METHOD'];
            $this->parametri = new FilteredMap(array_merge($_POST, $_GET));
            $this->kolacici = new FilteredMap($_COOKIE);
        
        }

        public function getFullURL() : string {
            return $this->domainName . $this->path;
        }

        public function getPath() : string {
            return $this->path;
        }

        public function getDomain() : string {
            return $this->domainName;
        }

        public function isPOST() : bool {
            return $this->method === self::POST;
        }

        public function isGET() : bool {
            return $this->method === self::GET;
        }

        public function getParametri() : FilteredMap {
            return $this->parametri;
        }

        public function getKolacici() : FilteredMap {
            return $this->kolacici;
        }

        
   

    }