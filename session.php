<?php
require_once(__DIR__.'/load.php');
session_start();
if(!isset($_SESSION["userid"])) {
    header("Location: login.php");
    exit();
}
?>