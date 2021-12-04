<?php 
    $customerid = $_GET['c_id'];

    echo "<h1>$customerid</h1>";


    
  
    $serverName = ".";
    $connectionInfo = array("Database"=>"TestDB","UID"=>"joy","PWD"=>"1234");
    
    $conn = sqlsrv_connect($serverName,$connectionInfo);
    
    if(!$conn){
        echo "Failed Connection";
        die(print_r(sqlsrv_errors(),true));
    }


    $sql = "EXEC sp_deleteCustomer @c_id = $customerid";



    $execute = sqlsrv_query($conn,$sql);

    if($execute){
        echo "<h1>Done</h1>";
        header("Location: saxparserdemo.php");
    }
    else{
        echo "<h1>Failed</h1>";
    }

?>