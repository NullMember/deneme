<?php
require_once(dirname(__DIR__).'/load.php');

function dbConnect(){
    if(file_exists(ABSPATH.'connector/auth.php')){
        require(ABSPATH.'connector/auth.php');
        mysqli_report(MYSQLI_REPORT_STRICT);
        try{
            $conn = new mysqli($dbhost, $dbuser, $dbpass, $db);
            $conn->set_charset("utf8mb4_general_ci");
            return $conn;
        }
        catch(mysqli_sql_exception $e){
            return false;
        }
    }
    else{
        return false;
    }
}
    
function dbDisconnect(mysqli $conn){
    $conn->close();
}

?>