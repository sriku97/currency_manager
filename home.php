<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css"> 
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>

<title>Spider Web Dev Task 2</title>

<style>
body
{
	background-color: gold;
	font-size: 140%;
}
h1
{
	color:floralwhite;
	text-align: center;
	font-size: 400%;
    font-family: Brush Script MT;
}
#maintext
{
	font-size: 160%;
	color: floralwhite;
}
ul
{
	list-style-type: none; 
}
li
{
	padding: 20px;
	padding-left: 30px;
	background-color: #2C3539;
	border: 1px dotted white;
	color: floralwhite;
	font-size: 140%;
	margin-right: 20px;
	font-family: arial;
}
li:hover
{
	background-color: #E5E4E2;
	color: #2C3539;
	cursor: pointer;
}
</style>

</head>

<body background="background.jpg">

<?php
    //setup database connection and create necessary database and tables
    $conn=new mysqli("localhost","root",""); 
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

    //to prevent errors when a call is made after 00:00:00
    if($prev_time[0]>82800)
        $cur_time+=86400;
    
    //if time difference is more than one hour or if it is the first entry, get new data and store
    if(($cur_time[0]-$prev_time[0])>3600||($cur_time[0]-$prev_time[0])==0)
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
    	<div class="col-lg-12">
            <h1>SPIDER WEB DEVELOPMENT TASK 2</h1>
            <br><br><br><br><br><br>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-4">
            <div id="menu">
                <ul>
                <a href="info"><li>Info</li></a>
                <a href="current_rates"><li>Current Rates</li></a>
                <a href="converter"><li>Currency Converter</li></a>
                <a href="graph"><li>Graph</li></a>
                </ul>
            </div>
        </div>
        <div class="col-lg-8"> 
            <div id="maintext">
                Lorem ipsum dolor sit amet, consectetur adipiscing elit. Curabitur condimentum vestibulum ex, et luctus velit rutrum vitae. Maecenas quis sem hendrerit, vulputate metus egestas, ultrices tortor. Maecenas consectetur ligula sit amet mauris scelerisque aliquet. Maecenas vehicula id ligula imperdiet consequat. Praesent venenatis ultricies nisl sit amet scelerisque. Donec vitae ligula magna. Sed quis fringilla nisi. Proin pulvinar orci in luctus malesuada. Cras volutpat ipsum vel velit pretium, id tempus enim hendrerit. Quisque nec ex eleifend, maximus mauris vel, rhoncus diam. Sed dignissim leo ut nisi suscipit, non pretium orci feugiat. Sed pellentesque pharetra tincidunt. Integer dictum metus et sapien condimentum rutrum.
            </div>
        </div>
    </div>
</div>
</body>

</html>