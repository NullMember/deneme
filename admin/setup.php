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
require_once(dirname(__DIR__).'/load.php');
header('Content-type: text/html; charset=utf-8');

if(isset($_POST["dbhost"]) && isset($_POST["db"]) && isset($_POST["dbuser"]) && isset($_POST["dbpass"])){
    $configfile = fopen(ABSPATH."connector/auth.php", "w");
    if($configfile){
        fwrite($configfile, "<?php\n");
        fwrite($configfile, "\$dbhost = '".htmlspecialchars($_POST['dbhost'])."';\n");
        fwrite($configfile, "\$db = '".htmlspecialchars($_POST['db'])."';\n");
        fwrite($configfile, "\$dbuser = '".htmlspecialchars($_POST['dbuser'])."';\n");
        fwrite($configfile, "\$dbpass = '".htmlspecialchars($_POST['dbpass'])."';\n");
        fwrite($configfile, "?>");
        fclose($configfile);
    }
    else{
        echo "<p class='text'>Veritabanı konfigürasyon dosyası bulundu.</p>";
    }
}
if(file_exists(ABSPATH."connector/auth.php")){
    require(ABSPATH.'connector/connector.php');
    echo "<p class='text'>Veritabanı konfigürasyon dosyası bulundu.</p>";
    echo "<p class='text'>Veritabanı bağlantısı kontrol ediliyor.</p>";
    $conn = dbConnect();
    if($conn){
        echo "<p class='text'>Veritabanı bağlantısı başarılı.</p>";
        echo "<p class='text'>Kullanıcı tablosu kontrol ediliyor.</p>";
        $result = mysqli_query($conn, "DESCRIBE users");
        if($result){
            echo "<p class='text'>Kullanıcı tablosu mevcut.</p>";
            echo "<p class='text'>Kullanıcı tablosu test ediliyor.</p>";
            $fetchedresult = $result->fetch_all();
            $isValid = true;
            foreach($fetchedresult as $key => $value){
                echo $key.". satir: ";
                foreach($value as $val){
                    echo $val.", ";
                }
                echo "<br>";
                switch($key){
                    case 0:
                        if(strcmp($value[0], "userid") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 1:
                        if(strcmp($value[0], "name") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 2:
                        if(strcmp($value[0], "surname") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 3:
                        if(strcmp($value[0], "email") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 4:
                        if(strcmp($value[0], "pass") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 5:
                        if(strcmp($value[0], "register_date") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 6:
                        if(strcmp($value[0], "validated") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 7:
                        if(strcmp($value[0], "validate_hash") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    case 8:
                        if(strcmp($value[0], "is_admin") == 0) $isValid &= true;
                        else $isValid &= false;
                        break;
                    default:
                        break;
                }
            }
            if($isValid){
                echo "<p class='text'>Kullanıcı tablosu testi başarılı.</p>";
            }
            else{
                echo "<p class='text'>Kullanıcı tablosu testi başarısız.</p>";
                echo "<p class='text'>Veri kaybını engellemek için eski kullanıcı tablosunun adı değiştiriliyor.</p>";
                $result = mysqli_query($conn, "ALTER TABLE users RENAME TO _users");
                if($result){
                    echo "<p class='text'>Tablonun ismi _users olarak değiştirildi.</p>";
                    echo "<p class='text'>Yeni kullanıcı tablosu oluşturuluyor.</p>";
                    $result = createUserTable($conn);
                    if($result){
                        echo "<p class='text'>Yeni kullanıcı tablosu oluşturuldu.</p>";
                    }
                    else{
                        echo "<p class='text'>Tablo oluşturulamadı, daha sonra tekrar deneyin.</p>";
                        exit();
                    }
                }
                else{
                    echo "<p class='text'>İsim değiştirme başarısız, daha sonra tekrar deneyin.</p>";
                    exit();
                }
            }
        }
        else{
            $error = mysqli_errno($conn);
            if($error == 1146){
                echo "<p class='text'>Kullanıcı tablosu mevcut değil.</p>";
                echo "<p class='text'>Yeni kullanıcı tablosu oluşturuluyor.</p>";
                $result = createUserTable($conn);
                if($result){
                    echo "<p class='text'>Yeni kullanıcı tablosu oluşturuldu.</p>";
                }
                else{
                    echo "<p class='text'>Tablo oluşturulamadı, daha sonra tekrar deneyin.</p>";
                    exit();
                }
            }
            else{
                echo "<p class='text'>Bilinmeyen bir hata oluştu, lütfen daha sonra tekrar deneyin.</p>";
            }
        }
    }
    else{
        ?>
        <div class="row">
            <div class="col-sm mx-auto" style="max-width: 500px;">
                <form method="POST" id="setup-form" class="form-base" action="setup.php">
                    <h1 class="h1">Veritabanı Kurulumu</h3>

                    <div class="form-floating">
                        <input type="text" id="dbhost" name="dbhost" class="form-control" placeholder="r" required>
                        <label for="dbhost" class="form-label">Veritabanı adresi</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" id="db" name="db" class="form-control" placeholder="r" required>
                        <label for="db" class="form-label">Veritabanı adı</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="text" id="dbuser" name="dbuser" class="form-control" placeholder="r" required>
                        <label for="dbuser" class="form-label">Veritabanı kullanıcı adı</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" id="dbpass" name="dbpass" class="form-control" placeholder="r" required>
                        <label for="dbpass" class="form-label">Veritabanı şifresi</label>
                    </div>
                    
                    <input type="submit" id="submit" value="Kaydet" class="btn-primary"><br>
                </form>
                <p class='text'>Veritabanına bağlanırken hata oluştu.</p>
                <p class='text'>Bilgileri doğru girdiğinizden emin olun.</p>
            </div>
        </div>
        <?php
    }
}
else{
    ?>
        <div class="row">
            <div class="col-sm mx-auto" style="max-width: 500px;">
                <form method="POST" id="setup-form" class="form-base" action="setup.php">
                    <h1 class="h1">Veritabanı Kurulumu</h3>

                    <div class="form-floating">
                        <input type="text" id="dbhost" name="dbhost" class="form-control" placeholder="r" required>
                        <label for="dbhost" class="form-label">Veritabanı adresi</label>
                    </div>

                    <div class="form-floating">
                        <input type="text" id="db" name="db" class="form-control" placeholder="r" required>
                        <label for="db" class="form-label">Veritabanı adı</label>
                    </div>
                    
                    <div class="form-floating">
                        <input type="text" id="dbuser" name="dbuser" class="form-control" placeholder="r" required>
                        <label for="dbuser" class="form-label">Veritabanı kullanıcı adı</label>
                    </div>

                    <div class="form-floating">
                        <input type="password" id="dbpass" name="dbpass" class="form-control" placeholder="r" required>
                        <label for="dbpass" class="form-label">Veritabanı şifresi</label>
                    </div>
                    
                    <input type="submit" id="submit" value="Kaydet" class="btn-primary"><br>
                </form>
                <p class='text'>Konfigürasyon dosyası eksik.</p>
            </div>
        </div>
    <?php
}

function createUserTable(mysqli $conn){
    return mysqli_query($conn, "CREATE TABLE `users` (
        `userid` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
        `name` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
        `surname` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
        `email` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
        `pass` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
        `register_date` DATETIME NOT NULL DEFAULT current_timestamp(),
        `validated` TINYINT(1) NOT NULL DEFAULT '0',
        `validate_hash` VARCHAR(100) NOT NULL COLLATE 'utf8mb4_general_ci',
        `is_admin` TINYINT(1) NOT NULL DEFAULT '0',
        PRIMARY KEY (`userid`) USING BTREE,
        UNIQUE INDEX `email` (`email`) USING BTREE,
        UNIQUE INDEX `validate_hash` (`validate_hash`) USING BTREE,
        INDEX `pass` (`pass`) USING BTREE
    )
    COLLATE='utf8mb4_general_ci'
    ENGINE=InnoDB");
}

function createDeleteEvent(mysqli $conn){
    global $dbuser, $dbhost;
    return mysqli_query($conn, "CREATE DEFINER=".$dbuser."@".$dbhost." EVENT `RemoveNonValidatedUsers`
                                    ON SCHEDULE
                                        EVERY 1 HOUR
                                    ON COMPLETION NOT PRESERVE
                                    ENABLE
                                    COMMENT 'Remove non validated user records after 24 hours'
                                    DO BEGIN
                                    DELETE FROM users WHERE validated=0 AND TIMESTAMPDIFF(DAY, register_date, CURRENT_TIMESTAMP());
                                END");
}

?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta2/dist/js/bootstrap.bundle.min.js" integrity="sha384-b5kHyXgcpbZJO/tY9Ul7kGkf1S0CWuKcCD38l8YkeH8z8QjE0GmW1gYU5S9FOnJ0" crossorigin="anonymous"></script>
    </body>
</html>