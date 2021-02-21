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
    <?php
require_once(__DIR__.'/load.php');
require(ABSPATH.'session.php');
require(ABSPATH.'connector/connector.php');
require(ABSPATH.'connector/helper.php');
header('Content-type: text/html; charset=utf-8');

$conn = dbConnect();
if($conn){
    $user = getUser($conn, $_SESSION["userid"]);
    if(isSuccesfull()){
?>
    <body class="bg-light">
        <nav class="navbar navbar-expand-sm navbar-light border">
            <div class="container-fluid">
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#">Ana Sayfa</a>
                        </li>
                        <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="kurslarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Kurslar
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="kurslarDropdown">
                            <li><a class="dropdown-item" href="#">Action</a></li>
                            <li><a class="dropdown-item" href="#">Another action</a></li>
                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                        </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">Ayarlar</a>
                        </li>
                        <?php
                        if($user["is_admin"]){
                            echo "<li class='nav-item'>
                                      <a class='nav-link' href='admin/'>Yönetici Paneli</a>
                                  </li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </nav>
    <div class="container-fluid py-5">

        <div class="row">
            <div class="col">
<?php
        if($user['validated']){
            echo "Hoşgeldin ".$user["name"]." ".$user["surname"]."<br>";
            echo $user["email"]."<br>";
            echo $user["register_date"]." tarihinde kayıt oldun.<br>";
            echo "Çıkış yapmak için <a href='logout.php'>buraya</a> tıklayın<br>";
        }
        else{
            echo "Hesabınız etkinleştirilmemiş.<br>";
            echo "Hesabınızı etkinleştirmek için mail adresinizi kontrol edin.<br>";
            echo "Çıkış yapmak için <a href='logout.php'>buraya</a> tıklayın<br>";
?>
<?php
        }
    }
}
?>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>