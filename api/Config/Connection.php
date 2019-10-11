<?php
/**
 * @author damith
 * @copyright 2011
 */

class createConnection //create a class for make connection
{
   var $host="localhost";
   var $Username="root";    // specify the sever details for mysql
   Var $Password="";
   var $Database="onlinetest";

    var $myconn;

    function connectToDatabase() // create a function for connect database
    {   

        $conn= mysqli_connect($this->host,$this->Username,$this->Password,$this->Database);

        if(!$conn)// testing the connection
        {
            die ("Cannot connect to the database");
        }

        else
        {

            $this->myconn = $conn;
            //echo "Connection established";

        }

        return $this->myconn;
    }

    function closeConnection() // close the connection
    {
        mysqli_close($this->myconn);
        //echo "Connection closed";
    }

}

?>
