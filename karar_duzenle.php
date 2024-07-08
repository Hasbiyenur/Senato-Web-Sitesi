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

       $aciklama=$_SESSION["senato"]." kullanıcısı karar_duzenle sayfasına giriş yaptı";
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
    <title>Karar Düzenleme</title>
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
                $id=@$_POST["idsi"];

                    if (@$_POST["kaydet"]){
                                    
                        $karar= $_POST["karar"];
                        $tarih= $_POST["tarih"];
                        $gundem= $_POST["gundem"];                       
                        $durum= $_POST["durum"];                      
                        $ekleyen="";       
                        $idal=$_POST["idal"];
                                
                        $data = [
                            'karar' => $karar,
                            'tarih' => $tarih,
                            'gundem' => $gundem,
                            'durum' => $durum,
                            'ekleyen' => $ekleyen,
                            'karar_id'=> $idal
                        ];

                        $sql = "UPDATE kararlar SET karar=:karar, tarih=:tarih, gundem=:gundem, durum=:durum, ekleyen=:ekleyen WHERE karar_id=:karar_id";
                        $stmt= $conn->prepare($sql);
                        $stmt->execute($data);
                                    
                        if ( $stmt ){
                            $aciklama=$_SESSION["senato"]." kullanıcı adına sahip kişi kararlar tablosunda ".$idal." idsine sahip veride düzenleme işlemi yaptı";
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
                <div class="col-lg-6 offset-md-3" style = "border: 1px solid; padding: 30px; background-color:white; margin-top: 50px">
                        <br><br>
                            <form action="" method="post">
                                <div class="form-group">
                                    <?php
                                        $query = $conn->query("SELECT * FROM kararlar WHERE karar_id = '".$id."'")->fetch(PDO::FETCH_ASSOC);    #bir kere çağırmak yeterli
                                    ?>
                                    <label for="karar">Karar:</label>
                                    <input type="text" class="form-control" name="karar" value="<?php echo $query["karar"]; ?>">
                                </div>
                                <div class="form-group">
                                    
                                    <label for="tarih">Tarih:</label>
                                    <input type="date" class="form-control" name="tarih" value="<?php echo $query["tarih"]; ?>">
                                </div>
                                <div class="form-group">
                                    
                                    <label for="gundem">Gündem:</label>
                                    <input type="text" class="form-control" name="gundem" value="<?php echo $query["gundem"]; ?>">
                                </div>
                                <div class="form-group">
                                    
                                    <label for="durum">Durum:</label>
                                    <input type="text" class="form-control" name="durum" value="<?php echo $query["durum"]; ?>">
                                </div>
                                    <input type="hidden" name="idal" value="<?php echo $_POST["idsi"];  ?>">
                                    <input type="submit" class="btn btn-success" value="Kaydet" name="kaydet">
                                    <a href="index.php" class="btn btn-secondary">Vazgeç</a>

                            </form>
                </div>
            </div>
        </div>
</body>
</html>
<?php } ?>