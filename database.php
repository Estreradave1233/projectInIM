<?php
    class Database{
        private $server = "localhost";
        private $username = "root";
        private $password = "";
        private $dbname = "improject";

        private $conn;
        private $state;
        private $errMsg;

        public function __construct()
        {
            try{
                $this->conn = new PDO("mysql:host=".$this->server . ";dbname=". $this->dbname,$this->username,$this->password);
                $this->conn->exec("set names utf8");
                $this->state = true;
                $this->errMsg = "Connected";
            }catch(PDOException $e){
                $this->state = false;
                $this->errMsg = "Error :" . $e->getMessage();
            }
        }
        protected function getState(){
            return $this->state;
        }
        protected function getErrMsg(){
            return $this->errMsg;
        }
        protected function getDb(){
            return $this->conn;
        }

        public function __destruct()
        {
            $this->conn = null;
        }
    }
?>