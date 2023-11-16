<?php 

include 'config.php';
if(isset($_POST['submit'])){

  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $email = mysqli_real_escape_string($conn, $_POST['email']);
  $pass = mysqli_real_escape_string($conn, md5($_POST['password']));
  $cpass = mysqli_real_escape_string($conn, md5($_POST['cpassword']));

  $select = mysqli_query($conn, "SELECT * FROM `data_user` WHERE email = '$email' AND password = '$pass'") or die('query failed');

  if(mysqli_num_rows($select) > 0 ){
    $message[] = 'pengguna telah tersedia!';
  } else{
    mysqli_query($conn, "INSERT INTO `data_user`(nama, email, password) VALUES('$nama', '$email', '$pass')") or die ('query failed');
    $message[] = 'register berhasil!';
    header('location:login.php');
  }
}

?>


<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar | Toko Panji</title>

    <!-- css file link -->
    <link rel="stylesheet" href="css/style.css">

  </head>
  <body>

  <?php 
  if(isset($message)){
    foreach($message as $message){
      echo '<div class="message" onclick="this.remove();">'.$message.'</div>';
    }
  }
  ?>

    <div class="form-container">
      <form action="" method="post">
        <h3>Register</h3>
        <input type="text" name="nama" required placeholder="enter username" class="box">
        <input type="email" name="email" required placeholder="enter email" class="box">
        <input type="password" name="password" required placeholder="enter password" class="box">
        <input type="password" name="cpassword" required placeholder="confirm password" class="box">
        <input type="submit" name="submit" class="btn" value="daftar">
        <p>Sudah punya akun? <a href="login.php">Masuk</a></p>
      </form>
    </div> 

  </body>
</html>
