<?php

    session_start();
    require_once ("db.php");
    date_default_timezone_set('Europe/Istanbul');
        
    if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip=$_SERVER["HTTP_CLIENT_IP"];
        }elseif (!empty($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            $ip=$_SERVER["HTTP_X_FORWARDED_FOR"];
        }else{
            $ip=$_SERVER["REMOTE_ADDR"];
        }
    
        $aciklama=$_SESSION["senato"]." kullanıcısı sistemden çıkış yaptı";
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
    session_destroy();
    header("Location:login.php");

?>