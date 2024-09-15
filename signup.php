<?php
session_start();
// require_once 'config/conndb.php';
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- <link rel="stylesheet" href="css/stysignup.css"> -->
  <link rel="stylesheet" href="css/signupsty.css">

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
      <h1>สมัครสมาชิก</h1>

      <form action="signup_db.php" method="post">
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
        <?php if (isset($_SESSION['warning'])) { ?>
          <div class="alert alert-warning" role="alert">
            <?php
            echo $_SESSION['warning'];
            unset($_SESSION['warning']);
            ?>
          </div>
        <?php } ?>

        <label for="firstname" class="">ชื่อ</label>
        <div class="custome-input">
          <input type="text" name="firstname" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกชื่อ')"
            oninput="this.setCustomValidity('')">
        </div>
        <label for="lastname" class="">นามสกุล</label>
        <div class="custome-input">
          <input type="text" name="lastname" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกนามสกุล')"
            oninput="this.setCustomValidity('')">
        </div>
        <label for="age" class="">อายุ</label>
        <div class="custome-input">
          <input type=age" name="age" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกอายุ')"
            oninput="this.setCustomValidity('')">
        </div>
        <label for="gender" class="">เพศ</label>
        <div class="custome-select">
          <select name="gender" id="gender">
            <option selected disabled value="" class="">โปรดระบุ</option>
            <option value="ชาย">ชาย</option>
            <option value="หญิง">หญิง</option>
            <option value="LGBTQ">LGBTQ</option>
          </select>
        </div>
        <label for="phone" class="">เบอร์โทร</label>
        <div class="custome-input">
          <input type=phone" name="phone" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกเบอร์โทร')"
            oninput="this.setCustomValidity('')">
        </div>
        <label for="email" class="">อีเมล</label>
        <div class="custome-input">
          <input type=email" name="email" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกอีเมล')"
            oninput="this.setCustomValidity('')">
        </div>
        <label for="typmen" class="">ประเภทสมาชิก</label>
        <select name="typemem" id="gender">
          <option value="3">ผู้ใช้ทั่วไป</option>
          <option value="1">นิสิตสาขาจิตวิทยา</option>
          <option value="2">อาจารย์สาขาจิตวิทยา</option>
        </select>
        <label for="username" class="">ชื่อผู้ใช้งาน</label>
        <div class="custome-input">
          <input type="text" name="username" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกชื่อผู้ใช้งาน')"
            oninput="this.setCustomValidity('')">
        </div>
        <label for="password" class="">รหัสผ่าน</label>
        <div class="custome-input">
          <input type=password" name="password" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณากรอกรหัสผ่าน')"
            oninput="this.setCustomValidity('')">
            <span onclick="togglePassword('password', this)" style="cursor: pointer;"></span>
        </div>
        <label for="conpassword" class="">ยืนยันรหัสผ่าน</label>
        <div class="custome-input">
          <input type="password" name="conpassword" placeholder="" required
            oninvalid="this.setCustomValidity('กรุณายืนยันรหัสผ่าน')"
            oninput="this.setCustomValidity('')">
          <span onclick="togglePassword('conpassword', this)" style="cursor: pointer;"></span>
        </div>

        <button type="submit" name="signup" class="create">Create</button>

        <div class="sign-in">
          <!-- <p></p> -->
          <a href="index.php">Sign in</a>
        </div>
      </form>
    </div>
  </main>

  <script>
    function togglePassword(fieldId, icon) {
      const passwordField = document.getElementById(fieldId);
      const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
      passwordField.setAttribute('type', type);
      icon.textContent = type === 'password' ? '👁️' : '🙈';
    }
  </script>

</body>

</html>