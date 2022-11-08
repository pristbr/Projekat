<?php

    namespace MuzickaProdavnica\Controllers;

use MuzickaProdavnica\Model\ProductModel;

    class ProductController extends AbstractController {

        public function showProducts(string $proizvodjac, string $tip) {

            $productModel = new ProductModel($this->db_name);

            $result = $productModel->getAll($proizvodjac, $tip);
            $this->monolog_log->info("Info : showing products to user!");
            if(isset($_SESSION['isLoggedIn']))
            {
                $korisnik_ime = $this->getUserName();

                return $this->render('product.twig', ['korisnikIme' => "Zdravo " . $korisnik_ime, 'result' => $result]);
            }

            return $this->render('product_no_login.twig', ['result' => $result]);
            
        }

        public function showProduct(string $proizvodjac, int $id, string $tip) {

            $productModel = new ProductModel($this->db_name);

            $result = $productModel->get($proizvodjac, $id, $tip);
            $this->monolog_log->info("Info : showing product to user!");
            if(isset($_SESSION['isLoggedIn'])) {
                return $this->render('product_show_login.twig', ['result' => $result, 'imeKorisnik' => $this->getUserName()]);
            }

            return $this->render('product_show_no_login.twig', ['result' => $result]);
        }

        public function buy(int $id) : string {
            if(!$this->request->isPOST()) {
                return "Failed to get request!";
            }

            if(!isset($_SESSION['isLoggedIn'])) {
                $route = "http://" . $this->request->getDomain() . '/user/load/loginpage';
                header("Location: $route");
                exit();
            }

            $productModel = new ProductModel($this->db_name);

            $user_id = $_SESSION['id'];

            $productModel->buy($user_id, $id);

            $this->monolog_log->info("Info : item bought");

            $route = "http://" . $this->request->getDomain() . '/user/load/mainpage';
            header("Location: $route");
            exit();
        }

    }

?>