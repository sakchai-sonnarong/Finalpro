<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

  <!-- <link rel="stylesheet" href="css/styleindex.css"> -->
  <link rel="stylesheet" href="css/styindex.css">

</head>
</head>

<body>
  <header>
    <div class="frame">
      <div class="logo">
        <img src="images/psylogo.png" alt="">
      </div>
      <div class="namecenter">
        <h1>ศูนย์ความเป็นเลิศทางจิตวิทยา<br></h1>
        <h2>PSYCHOLOGY EXCELLENCE CENTER</h2>
        <h3>คณะศึกษาศาสตร์ มหาวิทยาลัยมหาสารคาม</h3>
      </div>
    </div>
  </header>

  <main>
    <div class="container">
      <h1>เข้าสู่ระบบ</h1>
      <form action="signin_db.php" method="post">
        <?php if (isset($_SESSION['error'])) { ?>
          <div class="alert alert-danger" role="alert">
            <?php
            echo $_SESSION['error'];
            unset($_SESSION['error']);
            ?>
          </div>
        <?php } ?>
        <?php if (isset($_SESSION['success'])) { ?>
          <div class="alert alert-success" role="alert">
            <?php
            echo $_SESSION['success'];
            unset($_SESSION['success']);
            ?>
          </div>
        <?php } ?>
        <label for="username"><i class='bx bx-user'></i> ชื่อผู้ใช้งาน:</label>
        <div class="custome-input">
          <input type="username" name="username" placeholder="ชื่อผู้ใช้งานของคุณ" autocomplete="off">
          <!-- <i class='bx bx-user'></i> -->
        </div>
        <label for="password"> <i class='bx bx-lock-alt'></i> รหัสผ่าน:</label>
        <div class="custome-input">
          <input type="password" name="password" placeholder="รหัสผ่านของคุณ">
          <!-- <i class='bx bx-lock-alt'></i> -->
        </div>
        <button name="signin" class="signin">เข้าสู่ระบบ</button>
        <div class="create-account">
          <p>คุณต้องการสมัครสมาชิกใหม่?</p>
          <a href="signup.php">Create Account</a>
        </div>

      </form>
      <!-- <hr>
    <p><a href="signup.php" target="_blank">สมัครสมาชิก</a></p> -->
    </div>
  </main>

</body>

</html>