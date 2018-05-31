<?php

include_once ('class/class.Log.php');
include_once ('class/class.ErrorLog.php');
include_once ('class/class.AccessLog.php');

// if cross browser request options ignore
if($_SERVER['REQUEST_METHOD'] == "OPTIONS")
{
	exit();
}

//
// get data
//
$serversArray = json_decode(file_get_contents("php://input"), true);

//
// get date time for this transaction
//
$datetime = date("Y-m-d H:i:s");


// set variables
$enterdate = $datetime;

//
// messaging
//
$returnArrayLog = new AccessLog("logs/");

//------------------------------------------------------
// get admin user info
//------------------------------------------------------
// open connection to host
$DBhost = "localhost";
$DBschema = "udemy";
$DBuser = "tarryc";
$DBpassword = "tarryc";

//
// connect to db
//
$dbConn = @mysql_connect($DBhost, $DBuser, $DBpassword);
if (!$dbConn)
{
	$log = new ErrorLog("logs/");
	$dberr = mysql_error();
	$log->writeLog("DB error: $dberr - Error mysql connect. Unable to store myhttp information.");

	$rv = "";
	exit($rv);
}

if (!mysql_select_db($DBschema, $dbConn))
{
	$log = new ErrorLog("logs/");
	$dberr = mysql_error();
	$log->writeLog("DB error: $dberr - Error selecting db Unable to store myhttp information.");

	$rv = "";
	exit($rv);
}

//---------------------------------------------------------------
// loop and look for id. if find update else insert
// actually we will act as if only list sent to us is valid, so delete whats there and then insert
//---------------------------------------------------------------

//
// delete all original data
//
$sql = "DELETE FROM myhttptbl";

// print $sql;

$sql_result = @mysql_query($sql, $dbConn);
if (!$sql_result)
{
	$log = new ErrorLog("logs/");
	$sqlerr = mysql_error();
	$log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to store delete myhttp information.");
	$log->writeLog("SQL: $sql");

	$status = -100;
	$msgtext = "System Error: $sqlerr";
}

$inserts = 0;
$updates = 0;
foreach ($serversArray as $skey => $server) {
	// foreach ($svalue as $key => $value) {
	// 	print "<br>key:".$key;
	// 	print "<br>value:".$value;
	// 	print "<br><br>";
	// }

	// print "<br /><br />key is:".$skey;

	$id = $server['id'];
	$capacity = $server['capacity'];
	$name = $server['name'];

	// Do Insert
	$sql = "INSERT INTO  myhttptbl ( id,  capacity,  name,  changedate )
		VALUES ($id, $capacity,'$name','$enterdate')";

	$msg = "Insert successfull!";

	$inserts++;

	$sql_result = @mysql_query($sql, $dbConn);
	if (!$sql_result)
	{
		$log = new ErrorLog("logs/");
		$sqlerr = mysql_error();
		$log->writeLog("SQL error: $sqlerr - Error doing insert or update to db Unable to store myhttp information.");
		$log->writeLog("SQL: $sql");

		$status = -100;
		$msgtext = "System Error: $sqlerr";
	}



	// //
	// // see if id exists
	// //
	// $sql = "SELECT *  FROM myhttptbl WHERE id = '$id'";
	//
	// // print $sql;
	//
	// $sql_result = @mysql_query($sql, $dbConn);
	// if (!$sql_result)
	// {
	//     $log = new ErrorLog("logs/");
	//     $sqlerr = mysql_error();
	//     $log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to store myhttp information.");
	//     $log->writeLog("SQL: $sql");
	//
	//     $status = -100;
	//     $msgtext = "System Error: $sqlerr";
	// }
	//
	// $count = 0;
	// $count = mysql_num_rows($sql_result);
	// if ($count > 0)
	// {
	// 	// Do update
	// 	$sql = "UPDATE myhttptbl
	// 		SET id=$i, capacity=$capacity, name='$name', changedate='$enterdate'
	// 		WHERE id = $i";
	//
	// 	$updates++;
	// }
	// else
	// {
	// 	// Do Insert
	// 	$sql = "INSERT INTO  myhttptbl ( id,  capacity,  name,  changedate )
	// 		VALUES ($id, $capacity,'$name','$enterdate')";
	//
	// 	$msg = "Insert successfull!";
	//
	// 	$inserts++;
	// }

	// $sql_result = @mysql_query($sql, $dbConn);
	// if (!$sql_result)
	// {
	//     $log = new ErrorLog("logs/");
	//     $sqlerr = mysql_error();
	//     $log->writeLog("SQL error: $sqlerr - Error doing insert or update to db Unable to store myhttp information.");
	//     $log->writeLog("SQL: $sql");
	//
	//     $status = -100;
	//     $msgtext = "System Error: $sqlerr";
	// }
	//
	// print $sql;
}

//
// Build message
//
$msg = "Updates:".$updates." Added:".$inserts;

// close db connection
//
mysql_close($dbConn);

//
// pass back info
//

exit($msg);

?>
