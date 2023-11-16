<?php 

include 'config.php';
session_start();
$data_user = $_SESSION['data_user'];

if(!isset($data_user)){
 header('location:login.php');
};

if(isset($_GET['logout'])){
  unset($data_user);
  session_destroy();
  header('location:login.php');
};

if(isset($_POST['tambah_ke_keranjang'])){
  $product_name = $_POST['product_name'];
  $product_price = $_POST['product_price'];
  $product_img = $_POST['product_image'];
  $product_quantity = $_POST['product_quantity'];

  $select_keranjang = mysqli_query($conn, "SELECT * FROM `keranjang` WHERE nama = '$product_name' AND user_id = '$data_user'") 
  or die('query failed');

  if(mysqli_num_rows($select_keranjang) > 0){
    $message[] = 'product telah ditambah ke keranjang!';
  }else{
    mysqli_query($conn, "INSERT INTO `keranjang`(user_id, nama, price, img, quantity) 
    VALUES ('$data_user', '$product_name', '$product_price', '$product_img', '$product_quantity')") or die ('query failed');
    $message[] = 'product ditambah ke keranjang!';
  }
}

if(isset($_POST['update_keranjang'])){
  $update_quantity = $_POST['quantity_keranjang'];
  $update_id = $_POST['keranjang_id'];
  mysqli_query($conn, "UPDATE `keranjang` SET quantity = '$update_quantity' WHERE id='$update_id'") or die('query failed');
  $message[] = 'keranjang telah diperbarui';
}

if(isset($_GET['remove'])){
  $remove_id = $_GET['remove'];
  mysqli_query($conn, "DELETE FROM `keranjang` WHERE id = '$remove_id'") or die('query failed');
  header('location:index.php');
}

if(isset($_GET['delete_all'])){
  mysqli_query($conn, "DELETE FROM `keranjang` WHERE user_id = '$data_user'") or die('query failed');
  header('location:index.php');
}

?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title> Toko Panji</title>

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

    <div class="container">

      <div class="user-profile">

        <?php 
          
          $select_user = mysqli_query($conn, "SELECT * FROM `data_user` WHERE id = '$data_user'") or die ('query failed');
          if(mysqli_num_rows($select_user) > 0){
            $fetch_user = mysqli_fetch_assoc($select_user);
          };
        ?>

        <p> username : <span><?php echo $fetch_user['nama']; ?></span> </p>
        <p> email : <span><?php echo $fetch_user['email']; ?></span> </p>
        <div class="flex">
          <a href="login.php" class="btn">login</a>
          <a href="register.php" class="option-btn">register</a>
          <a href="index.php?logout=<?php echo $data_user; ?>" onclick="return confirm('apakah yakin ingin keluar?');"class="delete-btn">logout</a>
        </div>
      </div>

      <div class="company-profile">
      <a href="profile.php" class="btn">Profile Company</a>

      </div>

      <div class="products">

        <h1 class="heading">daftar menu</h1>

        <div class="box-container">
          <?php 
            $select_product = mysqli_query($conn, "SELECT * FROM `product`") or die ('query failed');
            if(mysqli_num_rows($select_product) > 0){
              while($fetch_product = mysqli_fetch_assoc($select_product)){
          ?>
            <form method="post" class="box" action="">
              <img src="img/<?php echo $fetch_product['img']; ?>" alt="">
              <div class="name"><?php echo $fetch_product['nama']; ?></div>
              <div class="price">Rp.<?php echo $fetch_product['price']; ?>/-</div>
              <input type="number" min="1" name="product_quantity" value="1">
              <input type="hidden" name="product_image" value="<?php echo $fetch_product['img']; ?>">
              <input type="hidden" name="product_name" value="<?php echo $fetch_product['nama']; ?>">
              <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
              <input type="submit" value="tambah ke keranjang" name="tambah_ke_keranjang" class="btn">
            </form>
          <?php 
              };
            };
          ?>
        </div>
      </div>

      <div class="shopping-cart">
        <h1 class="heading">keranjang belanja</h1>

        <table>
          <thead>
            <th>gambar</th>
            <th>nama</th>
            <th>harga</th>
            <th>jumlah</th>
            <th>total harga</th>
            <th>action</th>
          </thead>
          <tbody>
            <?php 
            $grand_total = 0;
              $keranjang_query = mysqli_query($conn, "SELECT * FROM `keranjang` WHERE user_id = '$data_user'") or die ('query failed');
              if(mysqli_num_rows($keranjang_query) > 0){
                while($fetch_keranjang = mysqli_fetch_assoc($keranjang_query)){
            ?>
              <tr>
                <td><img src="img/<?php echo $fetch_keranjang['img']; ?>" height="100" alt=""></td>
                <td><?php echo $fetch_keranjang['nama']; ?></td>
                <td>Rp.<?php echo $fetch_keranjang['price']; ?>/-</td>
                <td>
                  <form action="" method="post">
                    <input type="hidden" name="keranjang_id" value="<?php echo $fetch_keranjang['id']; ?>">
                    <input type="number" min="1" name="quantity_keranjang" value="<?php echo $fetch_keranjang['quantity']; ?>">
                    <input type="submit" name="update_keranjang" value="update" class="option-btn">
                  </form>
                </td>
                <td>Rp.<?php echo $sub_total = floatval($fetch_keranjang['price'] * $fetch_keranjang['quantity']); ?>/-</td>
                <td><a href="index.php?remove=<?php echo $fetch_keranjang['id']; ?>" class="delete-btn" 
                onclick="return confirm('yakin hapus dari keranjang?');">hapus</a></td>
              </tr>
            <?php 
              $grand_total += $sub_total;
                };
              }else{
                echo '<tr><td style="padding: 20px; text-transform:capitalize; text-align:center; colspan="6">tidak ada sereal yang ditambah</td></tr>';
              };
            ?>
            <tr class="table-bottom">
              <td colspan="4">grand total: </td>
              <td>Rp.<?php echo $grand_total; ?>/-</td>
              <td><a href="index.php?delete_all" onclick="return confirm('hapus semua dari keranjang?');" class="delete-btn 
              echo ($grand_total > 1)?'':'disabled'; ?>">hapus semua</a></td>
            </tr>
          </tbody>
        </table>

        <div class="cart-btn">
          <a href="#" class="btn <?php echo ($grand_total > 1)?'':'disabled'; ?>"> proses untuk checkout</a>
        </div>
      </div>
    </div>
  </body>
</html> 