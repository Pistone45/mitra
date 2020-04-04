<?php

class Connection{

    public function  dbConnection(){

        try{

            $conn = new PDO("mysql:host=localhost; dbname=mitra; port=3306",  'root', '');
            //var_dump($conn);
            $conn->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            return $conn;
        }catch (PDOException $e){
            echo 'ERROR: '. $e->getMessage();
        }

}

	
}

?>