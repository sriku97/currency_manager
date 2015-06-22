<?php
    //set up database connection
    $conn=new mysqli("localhost","root","");
    $conn->query("USE spider");
    $newcur1="USD".$cur1; //to match the format of the data in the database
    $newcur2="USD".$cur2; //to match the format of the data in the database
    $c1=mysqli_fetch_array($conn->query("SELECT currvalue FROM apidata WHERE currkey='".$newcur1."'"));
    $c2=mysqli_fetch_array($conn->query("SELECT currvalue FROM apidata WHERE currkey='".$newcur2."'"));
    if(is_numeric($val))
        echo $val*(float)$c2[0]/(float)$c1[0];
    else
    	echo "Wrong Input";
    $conn->close();
?>