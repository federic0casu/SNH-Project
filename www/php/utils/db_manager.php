<?php

//TODO: Maybe switch exceptions to the log?

class DBManager{

    private static $instance = null;
    private $connection = null;

    private $hostname;
    private $username;
    private $password;
    private $dbname;
    private $port;

    //Constructor of the DBManager class
    private function __construct(){
        $this->hostname = "db";
        $this->username = getenv("MYSQL_USER");
        $this->password = getenv("MYSQL_PASSWORD");
        $this->dbname = getenv("MYSQL_DATABASE");
        $this->port = '3306';
        $this->connect_to_db();
    }

    //Launched only once at class creation time
    private function connect_to_db() : void{
        $this->connection = new mysqli(
            $this->hostname,
            $this->username,
            $this->password,
            $this->dbname, 
            $this->port);
        if ($this->connection->connect_error) {
            throw new Exception('Connection Error (' . $this->connection->connect_errno . ') ' .
                $this->connection->connect_error);
        }
    }

    //The function used to get a DBManager instance in a singleton-like manner.
    public static function get_instance() : ?DBManager{
        if(self::$instance == null) {
            self::$instance = new DBManager();
        }
        return self::$instance;
    }

    function exec_query(string $query_type, string $query, 
                        array $parameters = [], string $param_types = ""){

        //Prepare query statement
        $statement = $this->connection->prepare($query);
        if(!$statement){
            throw new Exception('Prepare failed ('.$this->connection->connect_errno.') '.
                $this->connection->connect_error);
        }

        //Check if there are parameters that need binding
        if(!empty($parameters)){
            //Check that the number of parameters and their types is consistent
            if(count($parameters) !== strlen($param_types)){
                throw new Exception('Mismatched Bind params amount and types amount');
            }
            //Bind and check if it fails
            if(!$statement->bind_param($param_types, ...$parameters)){
                throw new Exception('Bind failed (' . $statement->connect_errno . ') ' .
                $statement->connect_error);
            }
        }

        //Execute the prepared statement and check if it fails
        $result = $statement->execute();
        if(!$result){
            throw new Exception('Execute failed (' . $statement->connect_errno . ') ' .
                $statement->connect_error);
        }

        //Return the query result depending on the type of operation performed
        if ($query_type == "SELECT") {
            $query_result = $statement->get_result();
            if (!$query_result) {
                throw new Exception('Get Result failed (' . $statement->connect_errno . ') ' .
                    $statement->connect_error);
            }
            //Extract the result as an array of associative arrays
            $result = $query_result->fetch_all(MYSQLI_ASSOC);
        }
        
        return $result;
    }

    //The destructor of the DBManager class.
    public function __destruct(){
        //Close db connection on destruction
        $this->connection->close();
        $this->connection = null;
        static::$instance = null;
    }

}

?>