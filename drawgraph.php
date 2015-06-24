<?php
    /*First a database connection is setup. Then the bardata variable which is in string format is parsed 
    and the necessary additions are made based on the user inputs and database queries. The result is 
    passed as a string and is parsed and assigned to the neccesary variable*/
    
    $conn=new mysqli("localhost","root",""); //if mysql is already installed use your own credentials
    $conn->query("USE spider");

    $obj=json_decode($bardata,true); //converts string to object
    array_push($obj['labels'],$curr); //adds user input to object

    $newcurr="USD".$curr; //to match the format of data in the database
    
    $value=mysqli_fetch_array($conn->query("SELECT currvalue FROM apidata WHERE currkey='".$newcurr."'"));
    $obj2=($obj['datasets'][0]);
    array_push($obj2['data'],(1/$value[0])); //based on user input
    $obj['datasets'][0]=($obj2);

    $newdata=json_encode($obj); //converts object back to string
    $conn->close();
    echo $newdata;
?>