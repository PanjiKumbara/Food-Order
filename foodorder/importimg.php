<?php 

include 'config.php';
if(isset($_POST['submit'])){

  $nama = mysqli_real_escape_string($conn, $_POST['nama']);
  $price = mysqli_real_escape_string($conn, $_POST['price']);
  $_FILES = mysqli_real_escape_string($conn, $_POST['img']);


  $select = mysqli_query($conn, "SELECT * FROM `product` WHERE nama = '$nama'") or die('query failed');

  if(mysqli_num_rows($select) > 0 ){
    $message[] = 'barang telah tersedia!';
  } else{
    mysqli_query($conn, "INSERT INTO `product`(nama, price, img) VALUES('$nama', '$price', '$_FILES')") or die ('query failed');
    $message[] = 'input berhasil!';
  }
}
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Import Img ke Database </title>

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
        <form action="" method="post" enctype="multipart/form-data">
          <h3>Import Img</h3>
          <input type="text" name="nama" required placeholder="enter name" class="box">
          <input type="int" name="price" required placeholder="enter price" class="box">
          <input type="file" name="img">
          <input type="submit" name="submit" class="btn" value="upload">
        </form>
      </div> 

  </body>
</html>

