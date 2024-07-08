<?php
    $mysqlsunucu = "localhost";
    $mysqlkullanici = "senatoproject";
    $mysqlsifre = "senato_123";
    try {
        $conn = new PDO("mysql:host=$mysqlsunucu;dbname=senato;charset=utf8", $mysqlkullanici, $mysqlsifre);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
       // echo "Bağlantı başarılı"; 
        }
    catch(PDOException $e)
        {
        //echo "Bağlantı hatası: " . 
       echo  $e->getMessage();
        }

?>