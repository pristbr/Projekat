<?php

    namespace MuzickaProdavnica\Controllers;

use Exception;
use MuzickaProdavnica\Exceptions\LoginValidationException;
    use MuzickaProdavnica\Exceptions\RegisterValidationException;
    use MuzickaProdavnica\Model\UserModel;

    class UserController extends AbstractController {
        public function showLoginPage() : string {
            return $this->render('login.twig', []);
        }

        public function showRegisterPage() : string {
            return $this->render('register.twig', ['pageTitle' => 'Gain&Volume d.o.o']);
        }

        public function register() : string {
            if(!$this->request->isPOST()) {
                return "Failed to get request";
            }
        
            $userModel = new UserModel($this->db_name);
            $id = 0;
            try {
            $id = $userModel->register($this->request);
            } catch (RegisterValidationException $e) {
                $this->monolog_log->error('Error : neuspesna registracija');
                return $this->render('register.twig', ['errName' => $e->get('name'),
                                                       'errSurname' => $e->get('surname'),
                                                       'errGrad' => $e->get('grad'),
                                                       'dobErr' => $e->get('dob'),
                                                       'errAdresa' => $e->get('adresa'),
                                                       'errZip' => $e->get('zip'),
                                                       'errTelefon' => $e->get('telefon'),
                                                       'errEmail' => $e->get('email'),
                                                       'errPassword' => $e->get('password'),
                                                       'errRetypePassword' => $e->get('retype_password')]);
            } catch (Exception $e) {
                $this->monolog_log->error('Error : kreiranje naloga sa vec unetim telefonskim brojem!');
                return $this->render('register.twig', ['errTelefon' => 'Vec unesen broj telefona!']);
            }

            $_SESSION['user_type'] = 'korisnik';
            $_SESSION['id'] = (int)$id;
            $_SESSION['isLoggedIn'] = true;
        

            $route = "http://" . $this->request->getDomain() . "/user/load/mainpage";

            if(isset($_SESSION['id'])) {
                header("Location: $route");
                exit();
            }
        }

        public function login() : string {
            if(!$this->request->isPOST()) {
                return "Failed to get request";
            }

            $userModel = new UserModel($this->db_name);
            $id = 0;
            try {
                $id = $userModel->login($this->request);
            } catch (LoginValidationException $e) {
                $this->monolog_log->error("Error: korisnik nesupesan tokom login!");
                return $this->render('login.twig', ['errEmail' => $e->get('email'), 'errPassword' => "Sifra : " . $e->get('password')]);
            }

            $_SESSION['user_type'] = 'korisnik';
            $_SESSION['id'] = (int)$id;
            $_SESSION['isLoggedIn'] = true;
            $this->monolog_log->info("Info : user logged in!");

            setcookie('user', $_SESSION['id']);

            $route = "http://" . $this->request->getDomain() . "/user/load/mainpage";

            if(isset($_SESSION['id'])) {
                header("Location: $route");
                exit();
            }
    }

        public function showAdminLogin() {
            return $this->render('Admin/admin_login.twig', []);
        }
        public function load_logged_in() : string {
            if(!isset($_SESSION['isLoggedIn'])) {
                $route = "http://" . $this->request->getDomain() . "/";
                header("Location: $route");
            }
            $name = $this->getUserName();

            return $this->render('main_page_login.twig', ['imeKorisnik' => "Zdravo " . $name]);
        }

        public function showMenadzerLogin() {
            return $this->render('Manager/menadzer_login.twig', []);
        }

        public function logout() {
            session_start();
            session_unset();
            session_destroy();

            $this->monolog_log->info("Info : user logged out!");

            $route = "http://" . $this->request->getDomain() . "/";
            setcookie('user', 0);

            header("Location: $route");
            exit();
        }
}

?>