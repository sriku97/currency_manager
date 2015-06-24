<?php
    /*Sets up database connection and gets the values based on user input and returns the value 
    as a string which is displayed inside a div*/


    //set up database connection
    $conn=new mysqli("localhost","root",""); //if mysql is already installed use your own credentials
    $conn->query("USE spider");
    $newcode="USD".$code; //to match the format of the data in the database
    $value=mysqli_fetch_array($conn->query("SELECT currvalue FROM apidata WHERE currkey='".$newcode."'"));
    echo "1 USD = ".$value[0]." ".$code;
    $conn->close();
?>