<?php
    
$serverName = ".";
$connectionInfo = array("Database"=>"TestDB","UID"=>"joy","PWD"=>"1234");

$conn = sqlsrv_connect($serverName,$connectionInfo);

if(!$conn){
    echo "Failed Connection";
    die(print_r(sqlsrv_errors(),true));
}

$strxml = $_POST["file"];

$sql = "EXEC sp_updateCustomerData @dataxml = '$strxml'";

////echo $strxml;

$execute = sqlsrv_query($conn,$sql);

if($execute){
    echo "<h1>Done</h1>";
    header("Location: saxparserdemo.php");
}
else{
    echo "<h1>Failed</h1>";
}

?>
