<?php

    namespace MuzickaProdavnica\Model;

    use Exception;
    use MuzickaProdavnica\Core\Request;
    use MuzickaProdavnica\Exceptions\EmptyRowException;
    use MuzickaProdavnica\Exceptions\LoginValidationException;
    use MuzickaProdavnica\Exceptions\RegisterValidationException;
    use MuzickaProdavnica\Exceptions\UserDoesNotExistException;
    use PDO;
    class AdminModel extends AbstractModel {


        private function textValidation(string $name) {
            if(empty($name)) {
                return "Prazno polje!";
            } elseif(ctype_alpha(str_replace(" ", "", $name)) == false ) {
                return "Moze samo sadrzati slova i spejsevi";
            }
            return false;
        }

        private function dateValidation(string $name) {

            $godina = (int)substr($name, 0, 4);

            if(empty($name)) {
                return "Prazno polje";
            } else if($godina <= 1900) {
                return "Godina pre 1900!";
            } 
            return false;
        }

        private function emailValidation(string $email) {

            if(empty($email)) {
                return "Prazno polje!";
            }

            $email_query = "SELECT * FROM nalog WHERE nalog_email = :email";

            $sth = $this->dbName->prepare($email_query);

            $sth->execute(['email' => $email]);

            $result = $sth->fetch();

            if(!empty($result)) {
                return "Email vec u korist!";
            }

            return false;
        }


        private function passwordValidation(string $password) {
            if(empty($password)) {
                return "Prazno polje!";
            } elseif(strlen($password) < 8) {
                return "Sifra mora imati vise nego 8 karaktera";
            }
            return false;
        }

        public function register(Request $request) {

            $validated = true;
            $nameErr = $surnameErr = $errGrad = $errDob = $errAdresa = $errZip = $errTelefon = "";
            $emailErr = $passwordErr = $retypepasswordErr = "";
            $telefon = $request->getParametri()->get('telefon');

            $proveri_telefon_sql = "SELECT * FROM korisnik WHERE korisnik_telefon = :telefon";

            $sth = $this->dbName->prepare($proveri_telefon_sql);

            $sth->execute(['telefon' => $telefon]);

            $result = $sth->fetch();

            if(!empty($result)) {
                throw new Exception('Broj telefona je vec unet');
            }

            $insert_korisnik_sql = "INSERT INTO korisnik(korisnik_ime, korisnik_prezime, korisnik_telefon, korisnik_grad, korisnik_datum_rodjenja, korisnik_adresa, korisnik_zip)
                                VALUES (:name, :surname, :telefon, :grad, :dob, :adresa, :zip)";
            
            $sth = $this->dbName->prepare($insert_korisnik_sql);

            $name = $request->getParametri()->get('name');
            $surname = $request->getParametri()->get('surname');
            $grad = $request->getParametri()->get('grad');
            $dob = $request->getParametri()->get('dob');
            $adresa = $request->getParametri()->get('adresa');
            $zip = $request->getParametri()->get('zip');

            $email = $request->getParametri()->get('email');
            $password = $request->getParametri()->get('korisnik_password');
            $retype_password = $request->getParametri()->get('korisnik_retype_password');


            
            if($this->textValidation($name)) {
                $validated = false;
                $nameErr = $this->textValidation($name);
            } else {
                $name = trim($name);
                $name = preg_replace('/\s\s+/', ' ', $name);
            }

            if($this->textValidation($surname)) {
                $validated = false;
                $surnameErr = $this->textValidation($surname);
            } else {
                $surname = trim($surname);
                $surname = preg_replace('/\s\s+/', ' ', $surname);
            }

            if(empty($grad)) {
                $validated = false;
                $errGrad = "Prazno polje!";
            }

            if($this->dateValidation($dob)) {
                $validated = false;
                $errDob = $this->dateValidation($dob);
            }

            if(empty($adresa)) {
                $validated = false;
                $errAdresa = "Prazno polje!";
            }

            if(empty($zip)) {
                $validated = false;
                $errZip = "Prazno polje!";
            } else if(strlen($zip) != 5) {
                $validated = false;
                $errZip = "Mora biti 5 cifara!";
            }

            if(empty($telefon)) {
                $validated = false;
                $errTelefon = "Prazno polje!";
            } else if(strlen($telefon) != 10) {
                $validated = false;
                $errTelefon = "Broj mora imati 10 cifara!";
            }

            if($this->emailValidation($email)) {
                $validated = false;
                $emailErr = $this->emailValidation($email);
            }

            if($this->passwordValidation($password)) {
                $validated = false;
                $passwordErr = $this->passwordValidation($password);
            }

            if($this->passwordValidation($retype_password)) {
                $validated = false;
                $retypepasswordErr = $this->passwordValidation($retype_password);
            }

            if($password != $retype_password) {
                $validated = false;
                $passwordErr = "Nije ista sifra!";
                $retypepasswordErr = "Nije ista sifra!";
            } else {
                $password = password_hash($password, PASSWORD_BCRYPT);
            }

            $exceptionArray = ['name' => $nameErr, 
                               'surname' => $surnameErr,
                               'grad' => $errGrad,
                               'dob' => $errDob,
                               'adresa' => $errAdresa,
                               'zip' => $errZip,
                               'telefon' => $errTelefon,
                               'email' => $emailErr,
                               'password' => $passwordErr,
                               'retype_password' => $retypepasswordErr];

            if(!$validated) {
                throw new RegisterValidationException('validacija nespesna', $exceptionArray);
            } else {
            $sth->execute(['name' => $name,
                           'surname' => $surname,
                           'telefon' => $telefon,
                           'grad' => $grad,
                           'dob' => $dob,
                           'adresa' => $adresa,
                           'zip' => $zip]);
            }

            $nameErr = $surnameErr = $errGrad = $errDob = $errAdresa = $errZip = $errTelefon = "";

            $get_korisnik_sql = "SELECT * FROM korisnik WHERE korisnik_telefon = :telefon";

            $sth = $this->dbName->prepare($get_korisnik_sql);

            $sth->execute(['telefon' => $telefon]);

            $result = $sth->fetch();

            if(empty($result)) {
                throw new EmptyRowException('nema reda');
            }
                                                
            $korisnik_id = $result['id'];


            $insert_nalog_sql = "INSERT INTO nalog(nalog_email, nalog_password, nalog_korisnik_id, nalog_korisnik_tip_id) VALUES (:email, :password, :id_korisnik, :id_tip)";

            $sth = $this->dbName->prepare($insert_nalog_sql);

            $sth->execute(['email' => $email,
                           'password' => $password,
                           'id_korisnik' => $korisnik_id,
                           'id_tip' => 2]);

            return $korisnik_id;
        }

        public function login(Request $request) {
            $validated = true;
            $emailErr = $passwordErr = "";

            $email = $request->getParametri()->get('email');
            $password = $request->getParametri()->get('korisnik_password');

            if(empty($email)) {
                $validated = false;
                $emailErr = "Prazno polje!";
            } 
            if (empty($password)) {
                $validated = false;
                $passwordErr = "Prazno polje!";
            } 

            $getId_query = "SELECT * FROM nalog WHERE nalog_email = :email";

            $sth = $this->dbName->prepare($getId_query);

            $sth->execute(['email' => $email]);

            $result = $sth->fetch();

            if(empty($result)) {
                $validated = false;
                $emailErr = "Pogresan unos!";
            } elseif($result['nalog_password'] != $password) {
                $validated = false;
                $passwordErr = "Wrong password!";
            }

            if(empty($result)) {
                throw new Exception('admin login failed!');
            }

            $errReturn = ['email' => $emailErr, 'password' => $passwordErr];

            if($validated == false) {
                throw new LoginValidationException('ne validan unos', $errReturn);
            } 

            return $result['id'];
        }

        public function getName() : string{

            $id = $_SESSION['id'];

            $get_name = "SELECT korisnik_ime FROM korisnik WHERE id = :id";

            $sth = $this->dbName->prepare($get_name);

            $sth->execute(['id' => $id]);

            $result = $sth->fetch();

            if(empty($result)) {
                throw new UserDoesNotExistException('ne postoji taj korisnik!');
            }

            return $result['korisnik_ime'];
        }

        public function getAllManagers() : array {
            $sql = "SELECT nalog.id, korisnik_ime, korisnik_prezime, nalog_email FROM korisnik INNER JOIN nalog ON nalog_korisnik_id = korisnik.id WHERE nalog_korisnik_tip_id = 2;";

            $sth = $this->dbName->prepare($sql);

            $sth->execute();

            return $sth->fetchAll(PDO::FETCH_ASSOC);
        }

        public function deleteManager(int $id) {
            $sql = "SELECT * FROM nalog WHERE nalog.id = :id";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['id' => $id]);

            $result = $sth->fetch(PDO::FETCH_ASSOC);

            if(empty($result)) {
                throw new Exception('ne postoji taj manager!');
            }

            $korisnik_id = $result['nalog_korisnik_id'];

            $sql = "UPDATE korisnik SET korisnik_ime = 'izbrisano', 
                                        korisnik_prezime = 'izbrisano', 
                                        korisnik_telefon = 'izbrisano . $id',
                                        korisnik_grad = 'izbrisano',
                                        korisnik_datum_rodjenja = '0000-00-00 00:00:00.000',
                                        korisnik_adresa = 'izbrisano',
                                        korisnik_zip = 'del' WHERE korisnik.id = :id;";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['id' => $korisnik_id]);

            $sql = "UPDATE nalog SET nalog_email = 'izbrisano . $id',
                                     nalog_password = 'izbrisano . $id' WHERE nalog.id = :id;";

            $sth = $this->dbName->prepare($sql);

            $sth->execute(['id' => $id]);

            return "";

        }

        
    }

?>