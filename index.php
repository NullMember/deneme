<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-giJF6kkoqNQ00vy+HMDP7azOuL0xtbfIcaT9wjKHr8RbDVddVHyTfAAsrekwKmP1" crossorigin="anonymous">
        <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Open+Sans'>
        <style>
            html, body, h1, h2, h3, h4, h5 {font-family: "Open Sans", sans-serif}
        </style>

        <title>Kurulum Sayfası</title>

    </head>
    <body class="bg-light">
    <div class="container-fluid py-5">
<?php
require_once(__DIR__.'/load.php');
require(ABSPATH.'session.php');
require(ABSPATH.'connector/connector.php');
require(ABSPATH.'connector/helper.php');

$conn = dbConnect();
if($conn){
    if(isset($_SESSION["userid"])){
        header("Location: dashboard.php");
        exit();
    }
    else{
        header("Location: login.php");
        exit();
    }
}
else{
?>
        <div class="row">
            <div class="col-sm mx-auto">
                <h4 class="h4">
                    Veritabanıyla iletişim kurulamadı, lütfen site yöneticisiyle iletişime geçin.
                </h4>
            </div>
        </div>
        <?php
}
        ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>