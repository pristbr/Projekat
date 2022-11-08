<?php

    namespace MuzickaProdavnica\Model;

use MuzickaProdavnica\Core\Request;
use MuzickaProdavnica\Exceptions\LoginValidationException;
use MuzickaProdavnica\Exceptions\UserDoesNotExistException;

    class ManagerModel extends AbstractModel {

        private function emailValidationLogin(string $email){

            if(empty($email)) {
                return "Prazno polje!";
            }

            $email_query = "SELECT * FROM nalog WHERE nalog_email = :email";

            $sth = $this->dbName->prepare($email_query);

            $sth->execute(['email' => $email]);

            $result = $sth->fetch();

            if(empty($result)) {
                return "Email ne postoji!";
            }

            if($result['nalog_korisnik_tip_id'] != 2) {
                return "Niste manager!";
            }

            return false;
        }


        private function passwordValidationLogin(string $password, string $email) {
            if(empty($password)) {
                return "Prazno polje!";
            }

            $oldPassword = $password;

            $password_query = "SELECT * FROM nalog WHERE nalog_email = :email";

            $sth = $this->dbName->prepare($password_query);

            $sth->execute(['email' => $email]);

            $result = $sth->fetch();

            $passwordDB = $result['nalog_password'];

            $oldPassword = password_hash($password, PASSWORD_BCRYPT);

            if(password_verify($oldPassword, $passwordDB)) {
                return $passwordDB . $oldPassword;
            }

            return false;
        }

    
        public function login(Request $request) {
            $validated = true;
            $emailErr = $passwordErr = "";

            $email = $request->getParametri()->get('email');
            $password = $request->getParametri()->get('korisnik_password');


            if(preg_match("/izbrisano|izbrisano\d+/", $email)) {
                $validated = false;
                $emailErr = "Izbrisan nalog!";
            }

            if($this->emailValidationLogin($email)) {
                $validated = false;
                $emailErr = $this->emailValidationLogin($email);
            }

            if($this->passwordValidationLogin($password, $email)) {
                $validated = false;
                $passwordErr = $this->passwordValidationLogin($password, $email);
            }

            $errReturn = ['email' => $emailErr, 'password' => $passwordErr];

            if($validated == false) {
                throw new LoginValidationException('ne validan unos', $errReturn);
            } 

            $getId_query = "SELECT * FROM nalog WHERE nalog_email = :email";

            $sth = $this->dbName->prepare($getId_query);

            $sth->execute(['email' => $email]);

            $result = $sth->fetch()['id'];

            return $result;
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


        
    }

?>