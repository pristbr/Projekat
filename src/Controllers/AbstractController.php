<?php

namespace MuzickaProdavnica\Controllers;

use MuzickaProdavnica\Core\Request;
use MuzickaProdavnica\Exceptions\UserDoesNotExistException;
use MuzickaProdavnica\Model\UserModel;
use MuzickaProdavnica\Utilities\DependencyInjection;

abstract class AbstractController {
    protected $request;
    protected $db_name;
    protected $file_config;
    protected $twig_view;
    protected $monolog_log;
    protected $di;
   

    public function __construct(DependencyInjection $di, Request $request) {
        $this->request = $request;
        $this->di = $di;


        $this->db_name = $di->getDependency('PDO');
        $this->monolog_log = $di->getDependency('Logger');
        $this->twig_view = $di->getDependency('Twig');
        $this->file_config = $di->getDependency('Utilities/Config');
    }

    protected function render(string $template, array $params): string {
        return $this->twig_view->load($template)->render($params);
    }

    protected function getUserName() : string {
        $userModel = new UserModel($this->db_name);

        $name = "";

        try {
            $name = $userModel->getName();
        } catch(UserDoesNotExistException $e) {
            $this->monolog_log->error("Error : user does not exist!");
        }

        return $name;
    }

}