<?php 
    $serverName = ".";
    $connectionInfo = array("Database"=>"TestDB","UID"=>"joy","PWD"=>"1234");
    
    $conn = sqlsrv_connect($serverName,$connectionInfo);
    
    if(!$conn){
        echo "Failed Connection";
        die(print_r(sqlsrv_errors(),true));
    }


    $sql = "EXEC sp_DeletedBackup";

    $stmt = sqlsrv_query($conn, $sql);
 
    sqlsrv_fetch($stmt);
    

    $xml = sqlsrv_get_field($stmt, 0, SQLSRV_PHPTYPE_STRING('UTF-8') );
    
    
    header('Content-type: text/xml');
    echo $xml;


?>