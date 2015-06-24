<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script>
function conv(code) 
{
	/*An ajax request is sent to the processing file through a laravel route with 
    the input as a parameter and the resultant value is displayed in a div*/
    var xmlhttp=new XMLHttpRequest();
	xmlhttp.onreadystatechange= function() {
		if(xmlhttp.readyState==4&&xmlhttp.status==200) 
		{
			//insert response into the result div if request is finished and response is ready
            document.getElementById("result").innerHTML=xmlhttp.responseText; 
		}
	}
	xmlhttp.open("GET","checkcurrentrate/"+code,true); //sends ajax request to a laravel route with one parameter
	xmlhttp.send();
}
</script>

<title>Spider Web Dev Task 2</title>

<style>
a,a:hover
{
	color:#2C3539;
}
h2
{
    text-align: center;
}
</style>

</head>

<body background="background.jpg">

<?php
    /*First, a database connection is setup, and the necessary database and tables are created if they
    don't exist. The table apidata is used to store the values obtained from the API and timestore is 
    used to store the time at which the database is updated.

    If no entry exists in the timestore table, the current time is inserted. The variable prev_time 
    gets the time at which the database was last updated as the number of seconds passed since 00:00:00 
    and the current time is also obtained in a similar way. 

    Then if the conditions to check for fresh data are true, the previous timestamp is deleted from timestore
    and the present time is added. Then the data from the API is obtained in JSON format and the necessary 
    data is extracted and stored in the table apidata after clearing it.*/

    //setup database connection and create necessary database and tables
    $conn=new mysqli("localhost","root",""); //if mysql is already installed use your own credentials
    $conn->query("CREATE DATABASE spider");
    $conn->query("USE spider");
    $conn->query("CREATE TABLE apidata(currkey char(6), currvalue float(10))");
    $conn->query("CREATE TABLE timestore(entrytime time)");

    //enter the current time into the database if no entry exists
    if(!(mysqli_fetch_array($conn->query("SELECT * FROM timestore"))))
        $conn->query("INSERT INTO timestore VALUES(now())");

    //get time when database was updated last
    $t=mysqli_fetch_array($conn->query("SELECT * FROM timestore"));
    $prev_time=mysqli_fetch_array($conn->query("SELECT TIME_TO_SEC('".$t[0]."')"));
    $cur_time=mysqli_fetch_array($conn->query("SELECT TIME_TO_SEC(now())"));


    //if time difference is more than one hour or if it is the first entry, get new data and store
    if(($cur_time[0]-$prev_time[0])>3600||($cur_time[0]-$prev_time[0])==0||$cur_time[0]<$prev_time[0])
    {
        $conn->query("DELETE FROM timestore"); //delete previous timestamp
        $conn->query("INSERT INTO timestore VALUES(now())"); //and replace with new one
        $data=file_get_contents("http://apilayer.net/api/live?access_key=1de7091150621fc633cca0a72bd6d0e1&format=1");
        $result=json_decode($data,true);
        $values=$result['quotes'];
        $conn->query("DELETE FROM apidata"); //for new incoming data
        foreach($values as $key=>$entry)
        {
            $conn->query("INSERT INTO apidata VALUES('".$key."',".$entry.")");
        }
    }
    $conn->close();
?>

<div class="container-fluid">
	<div class="row">
        <div class="jumbotron">
            <h2>Select Currency Code to view the value of the currency with respect to US Dollar</h2>
        </div>
	</div>
	<div class="row">
        <div class="col-lg-4">
            <div class="form-group">
            	<label for="sel1">Select Currency</label>
            	<select name='currencies' class="form-control" id="sel1" onchange="conv(this.value)">
            	    <?php echo file_get_contents("select.txt"); ?>
            	</select>
                <br><br>
            </div>
        </div>
        <div class="col-lg-8">    
            <div class="jumbotron" id="result" style="border: 1px solid black; font-size:150%">
            	1 USD = 1 USD
            </div>
        </div>
	</div>
    <div class="row">
    	<div class="col-lg-12">
        <h1 style="text-align:center"><a href="table" target="_BLANK">Click here to view the names of the currencies corresponding to each code</a></h1>
        </div>
    </div>
</div>
</body>

</html>