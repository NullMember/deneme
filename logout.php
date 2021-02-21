<?php
require_once(__DIR__.'/load.php');
session_start();
if(isset($_SESSION['userid'])){
    unset($_SESSION['userid']);
}
header("Location: index.php");
exit();
?>