<?php

    namespace MuzickaProdavnica\Controllers;

use Exception;
use MuzickaProdavnica\Exceptions\FailedProductChangeException;
use MuzickaProdavnica\Exceptions\FailedToAddArticleException;
use MuzickaProdavnica\Exceptions\LoginValidationException;
use MuzickaProdavnica\Exceptions\UserDoesNotExistException;
use MuzickaProdavnica\Model\ManagerModel;
use MuzickaProdavnica\Model\ProductModel;
use MuzickaProdavnica\Model\UserModel;

    class ManagerController extends AbstractController {

        public function loginManager() : string {
            if(!$this->request->isPOST()) {
                return "Failed to get request";
            }

            $userModel = new ManagerModel($this->db_name);
            $id = 0;
            try {
                $id = $userModel->login($this->request);
            } catch (LoginValidationException $e) {
                $this->monolog_log->error("Error : manager failed to log in!");
                return $this->render('Manager/menadzer_login.twig', ['errEmail' => $e->get('email'), 'errPassword' => "Sifra : " . $e->get('password')]);
            }

            $_SESSION['user_type'] = 'Admin';
            $_SESSION['id'] = (int)$id;
            $_SESSION['isLoggedIn'] = true;

            $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();
        }

        public function showManagerPanel() {
            return $this->render('Manager/manager_panel.twig', []);
        }

        public function dodajArtikl() {
            return $this->render('Manager/manager_artikl_dodaj.twig', []);
        }

        public function artiklDodaj() {
            if(!$this->request->isPOST()) {
                echo "Failed to get request";
            }

            $productModel = new ProductModel($this->db_name);
            try {
            $productModel->dodajArtikl($this->request);
            } catch (FailedToAddArticleException $e) {
                $this->monolog_log->error("Error: nije uspesno dodat nov proizvod");
                return $this->render('Manager/manager_artikl_dodaj.twig', ['errNaziv' => $e->get('ime'),
                                                                           'errOpis' => $e->get('opis'),
                                                                           'errCena' => $e->get('cena'),
                                                                           'errSlika' => $e->get('slika'),
                                                                           'errTip' => $e->get('tip')]);
            }
            $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();
        }

        public function izmeniArtikl() {

            $productModel = new ProductModel($this->db_name);

            $productModel = new ProductModel($this->db_name);

            $result = "";


            try {
            $result = $productModel->getAllProizvodi();
            }catch(Exception $e) {
                $this->monolog_log->error("Error : nema proizvodi u prodavnici!");
            $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

            header("Location: $route");
            exit();
            }

            return $this->render('Manager/manager_artikl.twig', ['result' => $result]);
        }

        public function artiklIzmeni(string $proizvodjac, int $id, string $tip) {
            $productModel = new ProductModel($this->db_name);

            $result = $productModel->get($proizvodjac, $id, $tip);

            return $this->render('Manager/manager_proizvod.twig', ['result' => $result]);
        }

        public function promeni(int $id) {

            $productModel = new ProductModel($this->db_name);

            try {
                $productModel->promeniArtikl($id, $this->request);
                } catch(FailedProductChangeException $e) {
                $this->monolog_log->info("Info : promena artikla");
                $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();
            }
    
            $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();
        }

        public function ibrisiArtikl() {
            
            $productModel = new ProductModel($this->db_name);

            $result = $productModel->getAllProizvodi();

            return $this->render('Manager/manager_artikl_izbrisi.twig', ['result' => $result]);
        }

        public function artiklIzbrisi(string $proizvodjac, int $id, string $tip) {

            $productModel = new ProductModel($this->db_name);

            $productModel->delete($proizvodjac, $id, $tip);

            $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();
        }

        public function sviKorisnici() {

            $userModel = new UserModel($this->db_name);

            $result = $userModel->getAllKorisnici();


            return $this->render('Manager/manager_korisnici.twig', ['result' => $result]);
        }

        public function korisnikProfil(int $id) {
            $userModel = new UserModel($this->db_name);
            
            $result = "";

            try {
            $resultKorisnik = $userModel->getKorisnik($id);
            $resultNalog = $userModel->getNalog($id);
            $resultNarudzbina = $userModel->getProizvodNarudzbina($id);
            } catch (UserDoesNotExistException $e) {
                $this->monolog_log->error("Error : user not found!");

                $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();
            }
            return $this->render('Manager/manager_korisnik.twig', ['korisnik' => $resultKorisnik, 'nalog' => $resultNalog, 'narudzbina' => $resultNarudzbina]);
        }

        public function potvrdiorder(int $id) {
            return $this->render('Manager/manager_narudzbina.twig', ['id' => $id]);
        }

        public function potvrdi(int $id) {

            $productModel = new ProductModel($this->db_name);

            try {
            $productModel->potvrdi($id, $this->request);
            } catch (Exception $e) {
                $this->monolog_log->error("Error : neuspeno izvrsena narudzbina!");
                $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

                header("Location: $route");
                exit();

            }

            $route = "http://" . $this->request->getDomain() . "/user/manager/managerpanel";

            header("Location: $route");
            exit();
        }

    }


?>