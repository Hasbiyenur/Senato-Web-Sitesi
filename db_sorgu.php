<?php
    require_once "db.php";
    require_once "login.php";

if (isset($_POST['gonder'])) {

    $kullanici_adi=$_POST['kuladi'];
    $kullanici_password=$_POST['pass'];
    
    $kullanicisor=$db->prepare("SELECT * FROM kullanicilar where kuladi=:kuladi AND sifre=:pass");
    $kullanicisor->execute(array(
    
    'kuladi' => $kuladi,
    'pass' => $sifre,
    ));
    
    echo $say=$kullanicisor->rowCount();
    
    }
    
    if ($say=="1") {
    header("Location:index.php");
    }
    


?>

#post işlemi ile veritabanından kullanıcı sorgula. 




                    '<form action="" method="post">
                            <div class="form-group">
                                <label for="karar">Karar</label>
                                <input type="text" class="form-control" name="karar">
                            </div>
                            <div class="form-group">
                                <label for="tarih">Tarih</label>
                                <input type="date" class="form-control" name="tarih">
                            </div>
                            <div class="form-group">
                                <label for="tarih">Durum</label>
                                <input type="text" class="form-control" name="durum">
                            </div>
                            <div class="form-group">
                                <label for="tarih">Ekleyen</label>
                                <input type="text" class="form-control" name="ekleyen">
                            </div>';




        <style>           html üzerinde giriş sayfası
        {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body{
            height: 100hv;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .container{
            display: flex;
            margin-top: 300px;
            flex-direction: column;
            width: 300px;
            padding: 15px;
            border: 1px solid
            skyblue;
            border-radius: 5px;
        }
        input{
            margin: 5px 0px;
            height: 35px;
            padding: 7px;
        }
        button{
            height: 35px;
            margin: 5px 0px;
            background-color: seashell;
            border: none;
            border-radius: 5px;
            color: #333;
        }
        button:hover{
            background-color:#333;
            color: skyblue;
        }
       
        
    </style>




 <!--<div class="container" id="login">      html üzerinde kullanıcı şifre input koyma
        <input type="username"
        placeholder="email">
        <input type="password"
        placeholder="password">
        <button>Giriş</button>
    </div> -->


<!--

<div>
    <input type="submit" class="btn btn-danger" value="Gönder" name="gonder">
</div> -->




                    // $kararlar = $_POST['karar'];  
                    // $tarihler = $_POST['tarih'];
                    // $durumlar = $_POST['durum'];
                    // $ekleyenler = $_POST['ekleyen'];







                    if(@$_POST["gonder"]){      
        $kullanici_adi = $_POST['kuladi'];  
	    $sifre = $_POST['pass'];

            $kullanici = $conn->prepare("INSERT INTO kullanicilar SET         kullanıcı kaydetme
                            kuladi = ?,
                            pass = ?,
                            ");
    $insert = $kullanici->execute(array(
        $kullanici_adi,$sifre
    ));
}






                    if (@$_POST["sil"]){
                        
                        $id=@$_POST["idsilme"];

                        $_POST['karar_id']= $id;

                        $silme = $conn->prepare("UPDATE FROM kararlar WHERE karar_id =:karar_id");
                        $delete = $silme->execute(array(
                        'karar_id' => $_POST['karar_id']
                        ));



                            