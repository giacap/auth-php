<?php 

    class Database {

        //DB params
        private $host = MYSQL_HOST_HERE;
        private $db_name = MYSQL_DB_NAME_HERE;
        private $username = MYSQL_USERNAME_HERE;
        private $password = MYSQL_PWD_HERE;
        public $conn;

        //DB connection
        public function connect() {
            $this->conn = null;

            $this->conn = new mysqli($this->host, $this->username, $this->password, $this->db_name);

            if($this->conn->connect_error){
                //header('Location: ./register.php?error=error');
                //exit();
                //die('Error: ' . $this->conn->connect_error);
                die('We are having issues.');
            }

            return $this->conn;

            
            

        }
    };


    

?>