<?php
    session_start();
    require_once "db.php";
    date_default_timezone_set('Europe/Istanbul');
    if (!isset($_SESSION["senato"])) {
        header("Location:cikis.php");
    }
    else{
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip=$_SERVER["HTTP_CLIENT_IP"];
        }elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip=$_SERVER["REMOTE_ADDR"];
        }
       // echo $ip;

        $aciklama=$_SESSION["senato"]." kullanıcısı kuladi_duzenle sayfasına giriş yaptı";
        $query = $conn->prepare("INSERT INTO logkaydi SET
            kadi = ?,
            ad = ?,
            soyad = ?,
            ip = ?,
            tarih = ?,
            saat = ?,
            aciklama = ?");
        $insert = $query->execute(array(
        $_SESSION["senato"],$_SESSION["ad"],$_SESSION["soyad"],$ip,date("Y-m-d"),date("H:i"),$aciklama
    ));
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kullanıcı Düzenle</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@3.3.7/dist/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
</head>
<body>
    <style>
        body{
            background-color: lightgrey;
            background-image: url("bgimage.jpg");
        }
    </style>

    <div class="container">
        <nav class="navbar navbar-expand-lg navbar-light" style="background-color: #e3f2fd;">
            <a class="navbar-brand" href="index.php">Ana Sayfa</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarScroll" aria-controls="navbarScroll" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarScroll">
                <ul class="navbar-nav mr-auto my-2 my-lg-0 navbar-nav-scroll" style="max-height: 100px;">
                    <li class="nav-item active">
                        <a class="nav-link" href="karar_ekle.php">Karar Ekle<span class="sr-only">(current)</span></a>
                    </li>
                    <?php
                    if($_SESSION["yetki"] == "admin"){
                        echo '<li class="nav-item active">
                                <a class="nav-link" href="kullanicilar.php">Kullanıcılar <span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                                <a class="nav-link" href="kuladi_ekle.php">Kullanıcı Ekle <span class="sr-only">(current)</span></a>
                        </li>';
                    }
                    ?>
                </ul>
            </div>
            <form class="form-inline">
            <?php echo $_SESSION["ad"]; echo ' '.$_SESSION["soyad"];?>
            <a class="btn btn-info my-2 my-sm-0" href="cikis.php">Çıkış</a>
            </form>
        </nav>
    </div>
    <div class="container">
            <div class="row">
            <?php
                require_once "db.php";
                $kulid=@$_POST["kulid"];

                    if (@$_POST["kaydet"]){
                                    
                        $ad= $_POST["ad"];
                        $soyad= $_POST["soyad"];
                        $email= $_POST["email"];                       
                        $sifre= $_POST["sifre"];                       
                        $kuladi= $_POST["kuladi"];                       
                        $durum= $_POST["durum"];                         
                        $yetki= $_POST["yetki"];                         
                        $kulidal=$_POST["kulidal"];
                                    
                        $data = [
                            'ad' => $ad,
                            'soyad' => $soyad,
                            'email' => $email,
                            'sifre' => $sifre,
                            'kuladi' => $kuladi,
                            'durum' => $durum,
                            'yetki' => $yetki,
                            'id'=> $kulidal
                        ];
                        $sql = "UPDATE kullanicilar SET ad=:ad, soyad=:soyad, email=:email, sifre=:sifre, kuladi=:kuladi, durum=:durum, yetki=:yetki WHERE id=:id";
                        $stmt= $conn->prepare($sql);
                        $stmt->execute($data);
                                    
                        if ( $stmt ){
                            $aciklama=$_SESSION["senato"]." kullanıcı adına sahip kişi kararlar tablosunda ".$kulidal." idsine sahip veride düzenleme işlemi yaptı";
                            $query = $conn->prepare("INSERT INTO logkaydi SET
                                kadi = ?,
                                ad = ?,
                                soyad = ?,
                                ip = ?,
                                tarih = ?,
                                saat = ?,
                                aciklama = ?");
                            $insert = $query->execute(array(
                            $_SESSION["senato"],$_SESSION["ad"],$_SESSION["soyad"],$ip,date("Y-m-d"),date("H:i"),$aciklama
                        ));
                            echo " <div class='col-lg-6 offset-md-3' style='margin-top: 6px'>
                                    <div class='alert alert-success' role='alert' style='text-align:center;'>
                                        <h3> Düzenleme işlemi başarılı. </h3>
                                    </div>
                                </div>";
                            header("Refresh:3");
                        }
                        else {
                            echo " <div class='col-lg-6 offset-md-3' style='margin-top: 6px'>
                                    <div class='alert alert-danger' role='alert' style='text-align:center;'>
                                        <h3> Düzenleme işlemi hatalı. </h3>
                                    </div>
                                </div>";
                            header("Refresh:3");
                        }        
                    }           
            ?>
                <div class="col-lg-6 offset-md-3" style = "border: 1px solid; padding: 30px; background-color:white; margin-top: 7px">
                        <br><br>
                            <form action="" method="post">
                                <div class="form-group">
                                    <?php
                                        $query = $conn->query("SELECT * FROM kullanicilar WHERE id = '".$kulid."'")->fetch(PDO::FETCH_ASSOC);    #bir kere çağırmak yeterli
                                    ?>
                                    <label for="ad">Ad:</label>
                                    <input type="text" class="form-control" name="ad" value="<?php echo $query["ad"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="soyad">Soyad:</label>
                                    <input type="text" class="form-control" name="soyad" value="<?php echo $query["soyad"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <input type="email" class="form-control" name="email" value="<?php echo $query["email"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="sifre">Şifre:</label>
                                    <input type="password" class="form-control" name="sifre" value="<?php echo $query["sifre"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="kuladi">Kullanıcı Adı:</label>
                                    <input type="text" class="form-control" name="kuladi" value="<?php echo $query["kuladi"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="durum">Durum:</label>
                                    <input type="text" class="form-control" name="durum" value="<?php echo $query["durum"]; ?>">
                                </div>
                                <div class="form-group">
                                    <label for="yetki">Yetki:</label>
                                    <input type="text" class="form-control" name="yetki" value="<?php echo $query["yetki"]; ?>">
                                </div>
                                    <input type="hidden" name="kulidal" value="<?php echo $_POST["kulid"];  ?>">
                                    <input type="submit" class="btn btn-success" value="Kaydet" name="kaydet">
                                    <a href="kullanicilar.php" class="btn btn-secondary">Vazgeç</a>

                            </form>
                </div>
            </div>
        </div>
    
</body>
</html>
<?php } ?>