<?php

    namespace MuzickaProdavnica\Model;

use Exception;
use MuzickaProdavnica\Core\Request;
use MuzickaProdavnica\Exceptions\FailedProductChangeException;
use MuzickaProdavnica\Exceptions\FailedToAddArticleException;
use MuzickaProdavnica\Exceptions\FilteredMapParametarException;
use PDO;

    class ProductModel extends AbstractModel {

        const classname = '\MuzickaProdavnica\Domain\Product\Product';

        public function getAllProizvodi() : array {
            $get_query = "SELECT * FROM proizvod;";

            $sth = $this->dbName->prepare($get_query);

            $sth->execute();

            $result = $sth->fetchAll(PDO::FETCH_ASSOC);

            if(empty($result)) {
                throw new Exception('nema proizvodi!');
            }

            return $result;
        }

        public function getAll(string $name, string $tip) : array {

            $get_query = "SELECT * FROM proizvod WHERE proizvod_proizvodjac = :ime_proizvodjac AND proizvod_tip = :tip;";

            $sth = $this->dbName->prepare($get_query);

            $sth->execute(['ime_proizvodjac' => $name, 'tip' => $tip]);

            return $sth->fetchAll(PDO::FETCH_CLASS, self::classname);
        }

        public function get(string $name, int $id, string $tip) : array {

            $get_query = "SELECT * FROM proizvod WHERE proizvod_proizvodjac = :proizvodjac AND id = :id AND proizvod_tip = :tip";

            $sth = $this->dbName->prepare($get_query);

            $sth->execute(['proizvodjac' => $name, 'id' => $id, 'tip' => $tip]);

            return $sth->fetchAll(PDO::FETCH_CLASS, self::classname);
        }

        public function buy(int $user_id, int $id) {
            $update_query = "INSERT INTO narudzbina(narudzbina_datum, nalog_id) VALUES (:datum, :id);";

            $sth = $this->dbName->prepare($update_query);

            $datum = date('Y-m-d H:i:s');

            $sth->execute(['datum' => $datum, 'id' => $user_id]);

            $getNarudzbinaId = "SELECT id FROM narudzbina WHERE narudzbina_datum = :datum AND nalog_id = :id;";

            $sth = $this->dbName->prepare($getNarudzbinaId);

            $sth->execute(['datum' => $datum, 'id' => $user_id]);

            $result = $sth->fetch();

            $update_query = "INSERT INTO narudzbina_detalji(id_proizvod, narudzbina_id) VALUES (:id_p, :id_n);";

            $sth = $this->dbName->prepare($update_query);

            $sth->execute(['id_p' => $id, 'id_n' => $result['id']]);

        }

        public function dodajArtikl(Request $request) {

            $validated = true;
            $proizvodjacErr = $imeErr = $opisErr = $cenaErr = $slikaErr = $tipErr = "";

            $proizvodjac = $ime = $opis = $cena = $slika = $tip = "";

            $proizvodjac = $request->getParametri()->get('proizvodjaci');
            $ime = $request->getParametri()->get('naziv_proizvoda');
            $opis = $request->getParametri()->get('opis_proizvoda');
            $cena = $request->getParametri()->get('cena_proizvoda');
            $slika = $request->getParametri()->get('slika_proizvoda');
            $tip = $request->getParametri()->get('tip_proizvoda');

            $tip = strtolower($tip);

            

            if(empty($ime)) {
                $validated = false;
                $imeErr = "Mora imati naziv";
            }

            if(empty($opis)) {
                $validated = false;
                $opisErr = "Mora imati opis";
            }
            if(empty($cena)) {
                $validated = false;
                $cenaErr = "Mora imati cena";
            }
            if(empty($slika)) {
                $validated = false;
                $slikaErr = "Mora imati sliku";
            }
            if(empty($tip)) {
                $validated = false;
                $tipErr = "Mora imati tip";
            }

            $errReturn = ['ime' => $imeErr, 'opis' => $opisErr, 'cena' => $cenaErr, 'slika' => $slikaErr, 'tip' => $tipErr];

            if(!$validated) {
                throw new FailedToAddArticleException('neuspeno dodat artikl', $errReturn);
            } 
            $sql = "INSERT INTO proizvod(proizvod_proizvodjac,
                                         proizvod_ime,
                                         proizvod_opis,
                                         proizvod_cena,
                                         proizvod_slika,
                                         proizvod_tip,
                                         proizvod_in_stock) VALUES (:proizvodjac, :ime, :opis, :cena, :slika, :tip, 1);";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['proizvodjac' => $proizvodjac,
                           'ime' => $ime,
                           'opis' => $opis,
                           'cena' => $cena,
                           'slika' => $slika,
                           'tip' => $tip]);

        }

        public function promeniArtikl(int $id, Request $request) {

            $validated = true;
            $imeErr = $opisErr = $cenaErr = $slikaErr = $tipErr = "";
            $proizvodjac = $ime = $opis = $cena = $slika = $tip = "";

            $proizvodjac = $request->getParametri()->get('proizvodjaci');
            $ime = $request->getParametri()->get('naziv_proizvoda');
            $opis = $request->getParametri()->get('opis_proizvoda');
            $cena = $request->getParametri()->get('cena_proizvoda');
            $slika = $request->getParametri()->get('slika_proizvoda');
            $tip = $request->getParametri()->get('tip_proizvoda');

            $in_stock = 0;

            try {
                $in_stock = (int)$request->getParametri()->get('in-stock');
            } catch (FilteredMapParametarException $e) {
                $in_stock = 0;
            }

            if(empty($ime)) {
                $validated = false;
                $imeErr = "Mora imati naziv";
            }

            if(empty($opis)) {
                $validated = false;
                $opisErr = "Mora imati opis";
            }
            if(empty($cena)) {
                $validated = false;
                $cenaErr = "Mora imati cena";
            }
            if(empty($slika)) {
                $validated = false;
                $slikaErr = "Mora imati sliku";
            }
            if(empty($tip)) {
                $validated = false;
                $tipErr = "Mora imati tip";
            }

   
            $tip = strtolower($tip);

            $errReturn = ['ime' => $imeErr, 'opis' => $opisErr, 'cena' => $cenaErr, 'slika' => $slikaErr, 'tip' => $tipErr];
           
            if(!$validated) {
                throw new FailedProductChangeException('pogresan unos!', $errReturn);
            } else {

            $sql = "UPDATE proizvod SET proizvod_proizvodjac = :proizvodjac,
                                        proizvod_ime = :ime,
                                        proizvod_opis = :opis,
                                        proizvod_cena = :cena,
                                        proizvod_slika = :slika,
                                        proizvod_tip = :tip,
                                        proizvod_in_stock = :stock WHERE proizvod.id = :id;";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['proizvodjac' => $proizvodjac,
                           'ime' => $ime,
                           'opis' => $opis,
                           'cena' => $cena,
                           'slika' => $slika,
                           'tip' => $tip,
                           'stock' => $in_stock,
                           'id' => $id]);
            }
            return $in_stock;

        }

        public function delete(string $name, int $id, string $tip) {

            $sql = "UPDATE proizvod SET proizvod_proizvodjac = 'izbrisano',
                                        proizvod_ime = 'izbrisano',
                                        proizvod_opis = 'izbrisano',
                                        proizvod_cena = 'izbrisano',
                                        proizvod_slika = 'izbrisano',
                                        proizvod_tip = 'izbrisano',
                                        proizvod_in_stock = 0 WHERE proizvod_proizvodjac = :proizvodjac AND id = :id AND proizvod_tip = :tip;";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['proizvodjac' => $name, 'id' => $id, 'tip' => $tip]);
        }

        public function potvrdi(int $id, Request $request) {

            $broj = $request->getParametri()->get('broj');
            $datum = $request->getParametri()->get('datum');

            $dat = date("Y-m-d H:i:s", strtotime($datum));

            if(empty($broj)) {
                throw new Exception('nije unet narudzbina broj!');
            }

            if(empty($datum)) {
                throw new Exception('nije unet datum narudzbine');
            }

            $sql = "UPDATE narudzbina SET narudzbina_broj = :broj,
                                          narudzbina_datum_slanja = :datum WHERE narudzbina.id = :id;";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['broj' => $broj, 'datum' => $dat, 'id' => $id]);

            }

    }