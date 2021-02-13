<?php
echo "Requested at:".date("h:i:sa");
?>
	<button onclick="history.go(-1);">Go Back</button><button onclick="location.href='http://learnmodeon.com/cricket/'">Go Online </button><br>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $inningsId  = $_POST["inningsId"];
    $score      = $_POST["score"];
    $overs      = $_POST["overs"];
    $ballByBall = $_POST["ballByBalls"];
	$wickets = $_POST["wktsdb"];
	$runRate = $_POST["crrdb"];
	$batsmenScore = $_POST["bmScoresdb"];
    
    if ($score < 0 or $overs < 0 or $inningsId == "") {
        echo "Invalid data provided. Please ensure data is valid!";
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
    
    $connection = db_connect();
    
    // Check connection
    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }
    
    // prepare and bind
    $selectQry = "select innings_id from gully_cricket where innings_id=?";
    if ($stmt = $connection->prepare($selectQry)) {
        $stmt->bind_param("s", $inningsId);
        $stmt->execute() or die(mysqli_stmt_error($stmt));
        $stmt->store_result();
        if ($stmt->num_rows === 0) {
            if ($stmt = $connection->prepare("INSERT INTO gully_cricket(innings_id,score,wickets,run_rate,overs,ball_by_ball,batsmen_Score) VALUES(?,?,?,?,?,?,?)")) {
                $stmt->bind_param("ssisiss", $inningsId, $score,$wickets,$runRate, $overs, $ballByBall,$batsmenScore);
                $stmt->execute() or die(mysqli_stmt_error($stmt));
                echo "New records created successfully";
            } else {
                $error = $connection->errno . ' ' . $connection->error;
                echo $error;
            }
        } else {
            $updateQry = "UPDATE gully_cricket SET score=?, overs=?, ball_by_ball=?,wickets=?,run_rate=?,batsmen_score=? where innings_id=?";
            if ($stmt = $connection->prepare($updateQry)) {
                $stmt->bind_param("sisisss", $score, $overs, $ballByBall,$wickets,$runRate, $batsmenScore,$inningsId);
                $stmt->execute() or die(mysqli_stmt_error($stmt));
                echo "Records updated successfully";
            } else {
                $error = $connection->errno . ' ' . $connection->error;
                echo $error;
            }
        }
    } else {
        $error = $connection->errno . ' ' . $connection->error;
        echo $error;
    }
    
    $stmt->close();
    $connection->close();
   echo "<script>history.go(-1);</script>";
   //header('Location: ' . $_SERVER['HTTP_REFERER']);
    
} else {
    echo "<b>whats up buddy?? Looks like you have landed on mars!</b>";
}


?>

