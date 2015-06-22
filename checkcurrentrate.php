<?php
    //set up database connection
    $conn=new mysqli("localhost","root",""); 
    $conn->query("USE spider");
    $newcode="USD".$code; //to match the format of the data in the database
    $value=mysqli_fetch_array($conn->query("SELECT currvalue FROM apidata WHERE currkey='".$newcode."'"));
    echo "1 USD = ".$value[0]." ".$code;
    $conn->close();
?>