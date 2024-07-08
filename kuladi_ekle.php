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

       $aciklama=$_SESSION["senato"]." kullanıcısı kuladi_ekle sayfasına giriş yaptı";
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
    <title>Kullanıcı Ekle</title>
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
                <a class="navbar-brand" href="kullanicilar.php">Ana Sayfa</a>
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

                    if(@$_POST["kaydet"]){
                            $ad = $_POST["ad"];
                            $soyad = $_POST["soyad"];
                            $email = $_POST["email"];
                            $sifre = $_POST["sifre"];
                            $kuladi = $_POST["kuladi"];
                            $durum = 1;
                            $yetki = $_POST["yetki"];


                            $query = $conn->prepare("INSERT INTO kullanicilar SET
                                            ad = ?,
                                            soyad = ?,
                                            email = ?,
                                            sifre = ?,
                                            kuladi = ?,
                                            durum = ?,
                                            yetki = ?");
                    $insert = $query->execute(array(
                        $ad,$soyad,$email,$sifre,$kuladi,$durum,$yetki
                    )); 

                    if ( $insert ){
                        $last_id = $conn->lastInsertId();

                        $aciklama=$_SESSION["senato"]." kullanıcı adına sahip kişi kullanicilar tablosuna ekleme işlemi yaptı";
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

                        echo " <div class='col-lg-6 offset-md-3' style='margin-top: 7px'>
                                <div class='alert alert-success' role='alert' style='text-align:center;'>
                                    <h3> Kaydetme işlemi başarılı. </h3>
                                </div>
                            </div>";
                        header("Refresh:3");
                    }
                    else {
                        echo " <div class='col-lg-6 offset-md-3' style='margin-top: 7px'>
                                <div class='alert alert-danger' role='alert' style='text-align:center;'>
                                    <h3> Kaydetme işlemi hatalı. </h3>
                                </div>
                            </div>";
                        header("Refresh:3");
                    }

                    }
                    
                ?>
                    <div class="col-lg-6 offset-md-3" style="margin-top:150px" >
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <h3 class="panel-title">Kullanıcı Ekle</h3>
                            </div>
                                <div class="panel-body">
                                    <form action="" method="post">    
                                        <table class="table table-bordered table-striped" style=" border:1px solid;">
                                            <tbody>
                                                <tr> 
                                                    <td>Ad</td>
                                                    <td><input type="text" name="ad" class="form-control" required></td>
                                                </tr>
                                                    <tr> 
                                                    <td>Soyad</td>
                                                    <td><input type="text" name="soyad" class="form-control" required></td>
                                                </tr>
                                                <tr> 
                                                    <td>Email</td>
                                                    <td><input type="email" name="email" class="form-control" required ></td>
                                                </tr>
                                                <tr> 
                                                    <td>Şifre</td>
                                                    <td><input type="password" name="sifre" class="form-control" required ></td>
                                                </tr>
                                                <tr> 
                                                    <td>K. Adı</td>
                                                    <td><input type="text" name="kuladi" class="form-control" required ></td>
                                                </tr>
                                                <tr> 
                                                    <td>Yetki</td>
                                                    <td><input type="text" name="yetki" class="form-control" placeholder="Lütfen buraya 'admin' veya 'kullanici' yazınız." required ></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                            <input type="submit" class="btn btn-success" value="Kaydet" name="kaydet" />
                                    </form>
                                </div>
                        </div>      
                    </div>
                    <?php
                        
                    ?>
            </div>
        </div>            
    
</body>
</html>
<?php } ?>