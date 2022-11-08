<?php

    namespace MuzickaProdavnica\Core;

use MuzickaProdavnica\Controllers\DefaultController;
use MuzickaProdavnica\Utilities\DependencyInjection;

class Router {
        private $di;
        private $data;

        private static $RegularExpressionPatterns = [
            'number' => '\d+',
            'string' => '\w+'
        ];

        public function __construct(DependencyInjection $di)
        {
            $this->di = $di;

            $json = file_get_contents(__DIR__ . '/../../config/routes.json');
            $this->data = json_decode($json, true);
        }


        public function getRoute(Request $request) : string {
            $path = $request->getPath();

            foreach ($this->data as $route => $info) {
                $regexRoute = $this->getRegexRoute($route, $info);
                if (preg_match("@^/$regexRoute$@", $path)) {
                    return $this->cnc($route, $info, $path, $request);
                }
            }

            $errorController = new DefaultController($this->di, $request);
            return $errorController->load_plain();
        }

        private function getRegexRoute(string $route, array $info) {
            if(isset($info['params'])) {
                foreach($info['params'] as $kljuc => $vrednost) {
                    $route = str_replace(':' . $kljuc, self::$RegularExpressionPatterns[$vrednost], $route);
                }
            }
            return $route;
        }

        private function cnc(string $route, array $info, string $path, Request $request) : string {
            $ControllerCreate = '\MuzickaProdavnica\Controllers\\' . $info['controller'] . 'Controller';
            $createController = new $ControllerCreate($this->di, $request);

            
            $parametri = $this->getParams($path, $route);
            return call_user_func_array([$createController, $info['method']], $parametri);
        }

        private function getParams(string $path, string $route) : array {
            $parametri = [];

            $pathParts = explode('/', $path);
            $routeParts = explode('/', $route);

            foreach($routeParts as $kljuc => $routePart) {
                if(strpos($routePart, ':') === 0) {
                    $ime = substr($routePart, 1);
                    $parametri[$ime] = $pathParts[$kljuc + 1];
                }
            }

            return $parametri;
            
        }

    }