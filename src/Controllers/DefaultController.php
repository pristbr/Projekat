<?php

    namespace MuzickaProdavnica\Controllers;

    class DefaultController extends AbstractController {
        public function load_plain() : string {
            session_unset();
            
            return $this->render('main_page_no_login.twig', []);
        }

        
    }

?>