<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
<script src="Chart.js"></script>

<script>
function add()
{
    /*The data required to draw the graph is in JSON format. Since an object can't be
    sent directly it is sent as a string and decoded at the processing file. New values 
    are inserted based on user input which is sent as another parameter and the new data
    is obtained and assigned to the required variable. The canvas is then refreshed to
    display the new graph*/
    var xmlhttp=new XMLHttpRequest();
    xmlhttp.onreadystatechange= function() {
        if(xmlhttp.readyState==4&&xmlhttp.status==200)
        {
            //resultant data is a string and is parsed and assigned to the bardata variable
            bardata=JSON.parse(xmlhttp.responseText);
            makeChart(); //canvas refresh
        }
    }
    var curr=document.getElementById("sel1").value;
    xmlhttp.open("GET","drawgraph/"+curr+"/"+JSON.stringify(bardata),true); //sends ajax request to a laravel route with three parameters
    xmlhttp.send();
}
</script>

<title>Spider Web Dev Task 2</title>

<style>
h2
{
	text-align: center;
}
#addgraph
{
	width:200px;
	height:50px;
	line-height: 50px;
	margin:auto;
	background-color: gray;
	font-size: 200%;
	border: 2px solid black;
	border-radius: 10px;
	display: table;
	text-align: center;
	font-family: impact;
	color: black;
	box-shadow: 5px 5px 5px black;
}
#addgraph:hover
{
	cursor: pointer;
}
#addgraph:active
{
	width: 190px;
	height: 45px;
	line-height: 45px;
	vertical-align: top;
    box-shadow: none;
}
a,a:hover
{
	color:#2C3539;
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
    		<h2>Select the currency you want to add to the graph and click Add!</h2>
    	</div>
    </div>
    <div class="row">
    	<canvas id="graph" width="500" height="300"></canvas>
    </div>
    <div class="row">
        <div class="col-lg-8">
        	<div class="form-group">
        		<select name='currencies' class="form-control" id="sel1">
            	    <?php echo file_get_contents("select.txt"); ?>
            	</select>
        	</div>
        </div>
        <div class="col-lg-4">
        	<div id="addgraph" onclick="add()">ADD</div>
        </div>
    </div>
    <div class="row">
    	<div class="col-lg-12">
        <h1 style="text-align:center"><a href="table" target="_BLANK">Click here to view the names of the currencies corresponding to each code</a></h1>
        </div>
    </div>
</div>

<script>
function makeChart()
{
    new Chart(ctx).Bar(bardata); //creates a new chart using charts.js
}
var bardata = {
	labels : [],
	datasets : [
	    {
		    fillColor : "rgba(0,0,255,0.4)",
		    data : []
	    }
	]
}
var ctx=document.getElementById("graph").getContext("2d");
makeChart();
</script>

</body>

</html>