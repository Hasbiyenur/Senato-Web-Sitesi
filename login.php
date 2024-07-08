<?php
    
    require_once "db.php";
    
    if(@$_POST["gonder"]){      
        $kullanici_adi = $_POST['kuladi'];  
	    $sifre = $_POST['pass'];


        $query = $conn->query("SELECT * FROM kullanicilar WHERE kuladi = '".$kullanici_adi."' and sifre = '".$sifre."' and durum=1")->fetch(PDO::FETCH_ASSOC);
        if ( $query ){
            session_start();
            $_SESSION["senato"] = $kullanici_adi;
            $_SESSION["ad"] = $query["ad"];
            $_SESSION["soyad"] = $query["soyad"];
            $_SESSION["yetki"] = $query["yetki"];
            header("Location:index.php");
        }
        else {
            echo "
            <div class='col-lg-4 offset-md-4 mt-5'>
            <div class='alert alert-danger' role='alert' style='text-align:center;'>
            <h3> Kullanıcı  adı veya şifre hatalı. </h3>
            </div>
            </div>
           ";
           header("Refresh:3");
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Senato Giriş </title>
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
        <div class="row">
            <div class="col-lg-4 offset-md-4 mt-5" style = "border: 1px solid; padding: 50px; background-color:white;">
                <h3 style="text-align: center;"> Senato Giriş </h3>
                    <br><br>
                        <form action="" method="post">
                            <div class="form-group">
                                <label for="kullaniciadi">Kullanıcı Adı</label>
                                <input type="text" class="form-control" name="kuladi">
                            </div>
                            <div class="form-group">
                                <label for="sifre">Şifre</label>
                                <input type="password" class="form-control" name="pass">
                            </div>
                                <input type="submit" class="btn btn-success" value="Gönder" name="gonder">
                        </form>
            </div>
        </div>
    </div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.min.js" integrity="sha384-+sLIOodYLS7CIrQpBjl+C7nPvqq+FbNUBDunl/OZv93DB7Ln/533i8e/mZXLi/P+" crossorigin="anonymous"></script>
</body>
</html>
