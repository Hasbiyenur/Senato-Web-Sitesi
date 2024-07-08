<?php
    session_start();
    require_once "db.php";
    date_default_timezone_set('Europe/Istanbul');
    //$query = $conn->query("SELECT * FROM kullanicilar WHERE kuladi = '".$_SESSION["senato"]."' and durum=1")->fetch(PDO::FETCH_ASSOC);
    if (!isset($_SESSION["senato"])) {
        header("Location:cikis.php");
    }else{
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip=$_SERVER["HTTP_CLIENT_IP"];
        }elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip=$_SERVER["REMOTE_ADDR"];
        }

        $aciklama=$_SESSION["senato"]." kullanıcısı index sayfasına giriş yaptı";
        $query= $conn->prepare("INSERT INTO logkaydi SET
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
    <title>Senato</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
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
                 
            <table class="table table-bordered table-striped" style="margin-top: 200px; border:1px solid;">
            <thead>
                
                <tr class="bg-info">
                <th scope="col">Sıra No</th>
                <th scope="col">Gündem</th>
                <th scope="col">Karar</th>
                <th scope="col">Tarih</th>
                <th scope="col">Durum</th>
                <th scope="col" colspan="2">İşlem</th>
               
                </tr>
            </thead>
            <tbody>
                <?php

                    $id=@$_POST["idsilme"];

                    if (@$_POST["sil"]){
                                    
                        $durum= @$_POST["durum"];
                                    
                        $data = [
                            'durum' => $durum,
                            'karar_id' => $id
                        ];
                        $sql = "UPDATE kararlar SET durum=:durum WHERE karar_id=:karar_id";
                        $stmt= $conn->prepare($sql);
                        $stmt->execute($data);

                        $aciklama=$_SESSION["senato"]." kullanıcı adına sahip kişi kararlar tablosunda ".$id." idsine sahip veride silme işlemi yaptı";
                        $query= $conn->prepare("INSERT INTO logkaydi SET
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
                        
                    }
                    $query = $conn->query("SELECT * FROM kararlar order by durum desc", PDO::FETCH_ASSOC);
                    if ( $query->rowCount() ){
                        $sayi = 0;
                        $durum = "";
                        foreach( $query as $row ){
                            $sayi++;
                            
                            // if ($row["durum"] == 1 ) {
                            //     $durum = "Aktif";
                            // }
                            // else {
                            //     $durum = "Pasif";
                            // }
                            $durum = ($row["durum"] == 1) ? "Aktif" : "Pasif" ;
                            echo '<tr>
                                    <td>'.$sayi.'</td>
                                    <td>'.$row["gundem"].'</td>
                                    <td>'.$row["karar"].'</td>
                                    <td>'.date("d.m.Y",strtotime($row["tarih"])).'</td>
                                    <td>'.$durum.'</td>';
                                    if ($row["durum"] == 1) {
                                        echo '
                                    <td>
                                        <form action="karar_duzenle.php" method="post">
                                        <input type="hidden" value="'.$row["karar_id"].'" name="idsi" />
                                        <input type="submit" class="btn btn-info btn-block" value="Düzenle" name="duzenle" />
                                  
                                    </form>
                                    </td>
                                    <td>
                                        <form action="" method="post">
                                            <input type="hidden" value="'.$row["karar_id"].'" name="idsilme" />  
                                            <input type="submit" class="btn btn-danger btn-block" onclick="return confirm(123)" value="Sil" name="sil" />
                                        </form>
                                    </td>';
                                    } else {
                                       echo '<td></td><td></td>';
                                    }
                            echo '</tr>';
                        }
                    }
                ?> 
            </tbody>
            </table>
        </div>
    </div>    
    </body>
    </html>

    <?php  }   ?>