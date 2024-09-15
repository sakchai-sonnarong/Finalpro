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

    <link rel="stylesheet" href="css/addfr.css">

    <title>เพิ่มข้อมูลแบบฟอร์ม</title>
</head>

<body>

    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-admin.php");
            ?>
        </nav>
    </header>
    <a href="javascript:void(0)" onclick="w3_open()" class="bi bi-list" id="MenuBtn"></a>
    
    <main>
        <!-- logo heard -->
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

        <div id="container">
            <h2>เพิ่มข้อมูลแบบฟอร์ม</h2>
            <form action="cruddocument_db.php" method="POST">
                <div class="detail">
                    <label for="document_form_data">ชื่อแบบฟอร์ม :</label><br>
                    <input type="text" id="document_form_data" name="document_form_data" required><br><br>
                </div>
                <button type="submit" name="add">บันทึก</button>
            </form>
        </div>
    </main>
    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>