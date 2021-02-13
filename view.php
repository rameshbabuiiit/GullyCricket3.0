<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
?>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="description" content="Score Card for Cricket">
  <meta name="keywords" content="cricket,score,batting,bowling,scorecard,cric,match">
  <meta name="author" content="Ramesh Babu Poludasu">
  <meta name="email" content="rameshbabuiiit@gmail.com">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate" />
  <meta http-equiv="Pragma" content="no-cache" />
  <meta http-equiv="Expires" content="-1" />
  <meta http-equiv="Content-Language" content="en">
</head>
<body>
<style>
#sboard{
	font-size:40px;
}
.scoreBoard{
	background-color: teal;
	border-collapse: collapse;
	color: white;
	table-layout: auto;
	width: 100%;
} 

#ballByBallTable{
	border-collapse: collapse;
	width: 100%;
	border: 2px solid #ddd;	
}


#batsmenScore tr:nth-child(even) {background: #CCC; font-weight:bold;}
#batsmenScore tr:nth-child(odd) {background: lightgreen;font-weight:bold;}

#ref{
	background-color: #e7e7e7;
	color: black;
	border: none;
	color: black;
	padding: 10px 15px;
	text-align: center;
	text-decoration: none;
	display: inline-block;
	font-size: 20px;
	margin: 4px 4px;
	cursor: pointer;
	border-radius: 10px;
}

#overNum{
	font-size : 25px;
}
</style>
 <form action='view.php' method='get'>Innings Id:<input type="text" name="inningsId"><input type="submit" value="Fetch"></form>
<?php
if ($_SERVER["REQUEST_METHOD"] == "GET") {
	

	$inningsId = @$_GET["inningsId"];
	
	if($inningsId==""){
		echo "Looks like you missed a moon in the night!";
		return;
	}	
		function db_connect()
    {
        static $connection;
        if (!isset($connection)) {
            $config     = parse_ini_file('./private/configram.ini');
            $connection = mysqli_connect($config['servername'], $config['username'], $config['password'], $config['dbname']);
        }
        
        if ($connection === false) {
            return mysqli_connect_error();
        }
        return $connection;
    }
  ?>
  <!-- <a href="view.php?inningsId=<php echo $inningsId ?>&time=<php echo time()?>"><button id="ref">Refresh</button></a> -->
    <a href="view.php?inningsId=<?php echo $inningsId ?>&time=<?php echo time()?>"><button id="ref">Refresh</button></a>
  <a href="./"><button id="ref">Go to scoring app</button></a> 
  <?php
  echo '<br><b>Request Time: </b><script type="text/javascript">var x = new Date(); document.write(x)</script>';
    $connection = db_connect();
    
    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
	
	$selectQry = "select * from gully_cricket where innings_id=?";
	$stmt = $connection->prepare($selectQry);
	$stmt->bind_param('s', $inningsId); 
	$stmt->execute();
	$result = $stmt->get_result();
	
	while ($row = $result->fetch_assoc()) {		
		//echo $row["score"];
		echo "<br><br><table  class=\"scoreBoard\" align=\"center\"><tr>".
				"<td><b id=\"sboard\"><i id=\"totScore\">".$row["score"]."</i>/<i id=\"wikts\">".$row["wickets"]."</i></b><i id=\"overNum\">(".$row["overs"]." overs)</i></td>".
				"<td><b>CRR: <i id=\"runRate\">".$row["run_rate"]."</i></b></td></tr></table><br><br>";
				
		echo "<table id=\"ballByBallTable\"><th>Over Wise Score</th><tr><td>".$row["ball_by_ball"]."</td></tr></table>";
	?>		
	<br><br><table id="batsmenScore"><tr><th colspan="2" style="background-color:white;color:black;">Batsmen Score Card</th></tr><tr><th>Batsman</th><th>Score</th></tr><?php echo $row["batsmen_Score"] ?><table>
	<?php
	}
	if(mysqli_num_rows($result) ==0){
		echo "<br>Please provide a valid inningsId";
	}
	
	$stmt->close();
	$connection->close();
}
?>
<hr><b>All Rights Reserved 2018-19&copy; rameshbabuiiit@gmail.com</b>
</body>
</html>