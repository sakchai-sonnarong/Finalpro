<?php

session_start();
if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/adduser-adm.css">
    <title>เพิ่มข้อมูลผู้ใช้งาน</title>
</head>

<body>
    <!-- Header Logo -->
    <header>
        <div id="logo-head">
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
        </div>
    </header>

    <!--  Container -->
    <main>
        <div class="container">
            <div class="head">
                <h2>เพิ่มข้อมูลผู้ใช้งาน</h2>
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
                <form action="cruduser_db.php" method="POST">
                    <div class="title-form">
                        <label for="member_name">ชื่อ :</label>
                        <input type="text" id="member_name" name="member_name" required>
                    </div>
                    <div class="title-form">
                        <label for="member_lastname">นามสกุล :</label>
                        <input type="text" id="member_lastname" name="member_lastname" required>
                    </div>
                    <div class="title-form">
                        <label for="typmen" class="">ประเภทสมาชิก :</label>
                        <select name="typemem" id="gender">
                            <option value="3">ผู้ใช้ทั่วไป</option>
                            <option value="1">นิสิตสาขาจิตวิทยา</option>
                            <option value="2">อาจารย์สาขาจิตวิทยา</option>
                        </select>
                    </div>
                    <div class="title-form">
                        <label for="๊username" class="">Username :</label>
                        <div class="custome-input">
                            <input type=username" name="username" placeholder="" required>
                        </div>
                    </div>
                    <div class="title-form">
                        <label for="password" class="">Password : </label>
                        <div class="custome-input">
                            <input type=password" name="password" placeholder="" required>
                        </div>
                    </div>
                    <div class="title-form">
                        <label for="conpassword" class="">Confirm Password :</label>
                        <div class="custome-input">
                            <input type=conpassword" name="conpassword" placeholder="" required>
                        </div>
                    </div>

                    <button type="submit" name="add" class="save-user">บันทึก</button>
                </form>
            </div>
        </div>
    </main>
</body>

</html>