<?php
session_start();
// require_once 'config/conndb.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Account Page</title>

    <!-- Link -->
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="css/stylecreate.css"> -->
    <link rel="stylesheet" href="css/stycreate.css">

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
                    <input type="text" name="firstname" placeholder="">
                </div>
                <label for="lastname" class="">นามสกุล</label>
                <div class="custome-input">
                    <input type="text" name="lastname" placeholder="">
                </div>
                <label for="age" class="">อายุ</label>
                <div class="custome-input">
                    <input type="number" name="age" placeholder="">
                </div>
                <label for="gender" class="">เพศ</label>
                <div class="custome-select">
                    <select name="gender" id="gender">
                        <option value="1">ชาย</option>
                        <option value="2">หญิง</option>
                        <option value="3">อื่นๆ</option>อื่นๆ</option>
                    </select>
                </div>
                <label for="phone" class="">เบอร์โทร</label>
                <div class="custome-input">
                    <input type=phone" name="phone" placeholder="">
                </div>
                <label for="email" class="">อีเมล์</label>
                <div class="custome-input">
                    <input type=email" name="email" placeholder="">
                </div>
                <label for="typmen" class="">ประเภทสมาชิก</label>
                <select name="typemem" id="typemem">
                    <option value="3">ผู้ใช้ทั่วไป</option>
                    <option value="1">นิสิตสาขาจิตวิทยา</option>
                    <option value="2">อาจารย์สาขาจิตวิทยา</option>
                </select>
        
                <label for="username" class="">ชื่อผู้ใช้</label>
                <div class="custome-input">
                    <input type=text" name="username" placeholder="">
                </div>
                <label for="password" class="">รหัสผ่าน</label>
                <div class="custome-input">
                    <input type="password" name="password" placeholder="">
                </div>
                <label for="conpassword" class="">ยืนยันรหัสผ่าน</label>
                <div class="custome-input">
                    <input type="password" name="conpassword" placeholder="">
                </div>
                <button class="create" type="submit" name="sigup">Create</button>
    
                <div class="sign-in">
                    <!-- <p></p> -->
                    <a href="index.php">Sign in</a>
                </div>
            </form>
        </div>
    </main>
    


    <script src="scriptcreate.js"></script>
</body>

</html>