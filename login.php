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
    header("Location: dashboard.php");
}
else if(isset($_POST["email"]) && isset($_POST["pass"])){
    $email = htmlspecialchars($_POST['email']);
    $pass = htmlspecialchars($_POST['pass']);
    if($email && $pass){
        $conn = dbConnect();
        if($conn){
            $userid = getUserID($conn, $email, $pass);
            if(isSuccesfull()){
                if($userid){
                    $_SESSION["userid"] = $userid;
                    
                }
                else{
                    $_SESSION["loginerror"] = "E-Posta veya Şifre yanlış.";
                }
            }
            else{
                $_SESSION["loginerror"] = "Giriş sırasında hata oluştu, lütfen tekrar deneyin.";
            }
        }
        else{
            $_SESSION["loginerror"] = "Veritabanıyla iletişim kurulamadı, lütfen site yöneticisiyle iletişime geçin.";
        }
    }
    else{
        $_SESSION["loginerror"] = "E-Posta ve Şifre boş bırakılamaz.";
    }
    $_SESSION["email"] = $email;
    header("Location: login.php");
}
else{
    ?>
        <div class="row">
            <div class="col-sm mx-auto" style="max-width: 500px;">

                <form method="POST" id="loginform" class="form-base">
                    <h1 class="h1">Giriş Yap</h1>

                    <div class="form-floating">
                        <input type="email" id="email" name="email" class="form-control" placeholder="r" value="<?php if(isset($_SESSION["email"])) echo $_SESSION["email"]; ?>" required>
                        <label for="email" class="form-label">E-Posta Adresiniz</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="password" id="pass" name="pass" class="form-control" placeholder="r" required>
                        <label for="pass" class="form-label">Şifreniz</label><br>
                    </div>

                    <?php 
                        if(!empty($_SESSION["loginerror"])){
                            echo "<div class='alert alert-danger'><label id='error'>".$_SESSION["loginerror"]."</label></div>";
                            unset($_SESSION["loginerror"]);
                        }
                    ?>

                    <input type="submit" id="submit" value="Giriş Yap" class="btn-primary">
                    <div class="alert alert-info">
                        <p class="form-text">Henüz kayıt olmadıysanız <a href='signup.php' class='link'>buraya</a> tıklayarak kayıt olabilirsiniz.</p>
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