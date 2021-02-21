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
require(ABSPATH.'connector/connector.php');
require(ABSPATH.'connector/helper.php');
header('Content-type: text/html; charset=utf-8');
session_start();
if(isset($_SESSION["userid"])){
    header("Location: ".ABSPATH."dashboard.php");
}
else if(isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["email"]) && isset($_POST["pass"]) && isset($_POST["passcheck"])){
    $name = htmlspecialchars($_POST['name']);
    $surname = htmlspecialchars($_POST['surname']);
    $email = htmlspecialchars($_POST['email']);
    $pass = htmlspecialchars($_POST['pass']);
    $passcheck = htmlspecialchars($_POST['passcheck']);
    
    if($name && $surname && $email && $pass && $passcheck){
        if(strcmp($pass, $passcheck) == 0){
            $conn = dbConnect();
            if($conn){
                addUser($conn, $name, $surname, $email, $pass);
                if(isSuccesfull()){
                    $_SESSION["signuperror"] = "Kayıt başarılı, <a href='login.php' class='link'>buraya</a> tıklayarak giriş yapabilirsiniz";
                }
                else{
                    $error = lastError();
                    if($error == 1062){
                        $_SESSION["signuperror"] = "Mail adresiniz zaten kayıtlı.";
                    }
                    else{
                        $_SESSION["signuperror"] = "Kayıt sırasında hata oluştu, lütfen tekrar deneyin.";
                    }
                }
            }
            else{
                $_SESSION["signuperror"] = "Veritabanıyla iletişim kurulamadı, lütfen site yöneticisiyle iletişime geçin.";
            }
        }
        else{
            $_SESSION["signuperror"] = "Şifreler uyuşmuyor, lütfen tekrar deneyin.";
        }
    }
    else{
        $_SESSION["signuperror"] = "Lütfen bütün alanları doldurun.";
    }
    $_SESSION["name"] = $name;
    $_SESSION["surname"] = $surname;
    $_SESSION["email"] = $email;
    header("Location: ".ABSPATH."signup.php");
}
else{
?>
        <div class="row">
            <div class="col-sm mx-auto" style="max-width: 500px;">

                <form method="POST" id="signupform" class="form-base">
                    <h1 class="h1">Kayıt Ol</h1>

                    <div class="form-floating">
                        <input type="text" id="name" name="name" class="form-control" placeholder="r" value="<?php if(isset($_SESSION["name"])) echo $_SESSION["name"]; ?>" required>
                        <label for="name" class="form-label">Adınız</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" id="surname" name="surname" class="form-control" placeholder="r" value="<?php if(isset($_SESSION["surname"])) echo $_SESSION["surname"]; ?>" required>
                        <label for="surname" class="form-label">Soyadınız</label>
                    </div>

                    <div class="form-floating">
                        <input type="email" id="email" name="email" class="form-control" placeholder="r" value="<?php if(isset($_SESSION["email"])) echo $_SESSION["email"]; ?>" required>
                        <label for="email" class="form-label">E-Posta Adresi</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" id="pass" name="pass" class="form-control" placeholder="r" required>
                        <label for="pass" class="form-label">Şifre</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" id="passcheck" name="passcheck" class="form-control" placeholder="r" required>
                        <label for="passcheck" class="form-label">Şifreyi Tekrar Girin</label>
                    </div>

                    <?php 
                        if(!empty($_SESSION["signuperror"])){
                            echo "<div class='alert alert-danger'><label id='error'>".$_SESSION["signuperror"]."</label></div>";
                            unset($_SESSION["signuperror"]);
                        }
                    ?>

                    <input type="submit" id="submit" value="Kayıt Ol" class="btn-primary">
                    <div class="alert alert-info">
                        <p class="form-text">Eğer kayıtlıysanız <a href='login.php' class='link'>buraya</a> tıklayarak giriş yapabilirsiniz.</p>
                    </div>
                </form>
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