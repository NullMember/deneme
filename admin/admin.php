<?php
require_once(dirname(__DIR__).'/load.php');
require(ABSPATH.'connector/connector.php');
require(ABSPATH.'connector/helper.php');
session_start();
if(!isset($_SESSION['userid'])){
    header('Location: ..');
    exit();
}
$conn = dbConnect();
if($conn){
    $user = getUser($conn, $_SESSION['userid']);
    if(!$user['is_admin']){
        header('Location: ..');
        exit();
    }
}
else{
    header('Location: ..');
    exit();
}
?>