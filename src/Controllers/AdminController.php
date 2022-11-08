<?php

    namespace MuzickaProdavnica\Controllers;

use Exception;
use MuzickaProdavnica\Exceptions\FailedProductChangeException;
use MuzickaProdavnica\Exceptions\FailedToAddArticleException;
use MuzickaProdavnica\Exceptions\LoginValidationException;
use MuzickaProdavnica\Exceptions\RegisterValidationException;
use MuzickaProdavnica\Exceptions\UserDoesNotExistException;
use MuzickaProdavnica\Model\AdminModel;
use MuzickaProdavnica\Model\ProductModel;
use MuzickaProdavnica\Model\UserModel;


    class AdminController extends AbstractController {

        public function loginAdmin() : string {
            if(!$this->request->isPOST()) {
                return "Failed to get request";
            }

            $userModel = new AdminModel($this->db_name);
            $id = 0;
            try {
                $id = $userModel->login($this->request);
            } catch (LoginValidationException $e) {
                $this->monolog_log->error("Error : admin failed to log in!");
                return $this->render('Admin/admin_login.twig', ['errEmail' => $e->get('email'), 'errPassword' => "Sifra : " . $e->get('password')]);
            }

            $_SESSION['user_type'] = 'Admin';
            $_SESSION['id'] = (int)$id;
            $_SESSION['isLoggedIn'] = true;

            $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
        }

        public function addManager() {
            return $this->render("Admin/admin_panel_manager.twig", []);
        }

        public function deleteManager() {
            
            $adminModel = new AdminModel($this->db_name);

            $result = $adminModel->getAllManagers();

            return $this->render("Admin/admin_panel_delete.twig", ['result' => $result]);
        }

        public function managerDelete(int $id) : string {

            $adminModel = new AdminModel($this->db_name);

            $adminModel->deleteManager($id);

            $result = $adminModel->getAllManagers();

            return $this->render("Admin/admin_panel_delete.twig", ['result' => $result]);
        }

        public function registerManager() {
            if(!$this->request->isPOST()) {
                echo "Failed to get request!";
            }

            $adminModel = new AdminModel($this->db_name);

            try {
            $adminModel->register($this->request);
            }catch(RegisterValidationException $e) {
                $this->monolog_log->error('Error : neuspesna registracija moderatora');
                return $this->render('Admin/admin_panel_manager.twig', ['errName' => $e->get('name'),
                                                       'errSurname' => $e->get('surname'),
                                                       'errGrad' => $e->get('grad'),
                                                       'dobErr' => $e->get('dob'),
                                                       'errAdresa' => $e->get('adresa'),
                                                       'errZip' => $e->get('zip'),
                                                       'errTelefon' => $e->get('telefon'),
                                                       'errEmail' => $e->get('email'),
                                                       'errPassword' => $e->get('password'),
                                                       'errRetypePassword' => $e->get('retype_password')]);
            }
            $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
        }

        public function dodajArtikl() {
            return $this->render('Admin/admin_artikl_dodaj.twig', []);
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
                    return $this->render('Admin/admin_artikl_dodaj.twig', ['errNaziv' => $e->get('ime'),
                                                                               'errOpis' => $e->get('opis'),
                                                                               'errCena' => $e->get('cena'),
                                                                               'errSlika' => $e->get('slika'),
                                                                               'errTip' => $e->get('tip')]);
                }
            $this->monolog_log->info("Info : dodat nov artikl");

            $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
        }

        public function izmeniArtikl() {

            $productModel = new ProductModel($this->db_name);

            $result = "";


            try {
            $result = $productModel->getAllProizvodi();
            }catch(Exception $e) {
                $this->monolog_log->error("Error : nema proizvodi u prodavnici!");
            $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

            header("Location: $route");
            exit();
            }
            return $this->render('Admin/admin_artikl.twig', ['result' => $result]);
        }

        public function artiklIzmeni(string $proizvodjac, int $id, string $tip) {
            $productModel = new ProductModel($this->db_name);

            $result = $productModel->get($proizvodjac, $id, $tip);

            return $this->render('Admin/admin_proizvod.twig', ['result' => $result]);
        }

        public function promeni(int $id) {

            $productModel = new ProductModel($this->db_name);

            $result = "";

            try {
            $result = $productModel->promeniArtikl($id, $this->request);
            } catch(FailedProductChangeException $e) {
            $this->monolog_log->info("Info : promena artikla");
            }


            $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
        }

        public function ibrisiArtikl() {
            $productModel = new ProductModel($this->db_name);

            $result = $productModel->getAllProizvodi();

            return $this->render('Admin/admin_artikl_izbrisi.twig', ['result' => $result]);
        }

        public function artiklIzbrisi(string $proizvodjac, int $id, string $tip) {
            $productModel = new ProductModel($this->db_name);

            $productModel->delete($proizvodjac, $id, $tip);

            $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
        }

        public function sviKorisnici() {

            $userModel = new UserModel($this->db_name);

            $result = $userModel->getAllKorisnici();

            return $this->render('Admin/admin_korisnici.twig', ['result' => $result]);
        }

        public function showAdminPanel() {
            return $this->render('Admin/admin_panel.twig', []);
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

                $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
            }
            return $this->render('Admin/admin_korisnik.twig', ['korisnik' => $resultKorisnik, 'nalog' => $resultNalog, 'narudzbina' => $resultNarudzbina]);
        }

        public function exportExcel(int $id) {

            $this->monolog_log->info("Info : excel file created!");

            header("Content-Type: application/xls");
            header("Content-Disposition:attachment; filename=download.xls");

            $userModel = new UserModel($this->db_name);
            
            $result = "";
            try {
                $resultKorisnik = $userModel->getKorisnik($id);
                $resultNalog = $userModel->getNalog($id);
                $resultNarudzbina = $userModel->getProizvodNarudzbina($id);
            } catch (UserDoesNotExistException $e) {
                $this->monolog_log->error("Error : user not found!");
                $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
            }
            return $this->render('Admin/admin_excel.twig', ['korisnik' => $resultKorisnik, 'nalog' => $resultNalog, 'narudzbina' => $resultNarudzbina]);
        }

        public function exportPDF(int $id) {
           
            $this->monolog_log->info("Info : pdf file created!");

            header('Content-type: application/pdf');
            header('Content-Disposition: attachment; filename="download.pdf"');
            flush();
            ob_flush();
            $userModel = new UserModel($this->db_name);
            
            $result = "";
            try {
                $resultKorisnik = $userModel->getKorisnik($id);
                $resultNalog = $userModel->getNalog($id);
                $resultNarudzbina = $userModel->getProizvodNarudzbina($id);
            } catch (UserDoesNotExistException $e) {
                $this->monolog_log->error("Error : user not found!");
                $route = "http://" . $this->request->getDomain() . "/user/admin/adminpanel";

                header("Location: $route");
                exit();
            }

            return $this->render('Admin/admin_excel.twig', ['korisnik' => $resultKorisnik, 'nalog' => $resultNalog, 'narudzbina' => $resultNarudzbina]);

        }
    }