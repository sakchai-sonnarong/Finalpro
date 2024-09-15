<?php

session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

if (isset($_POST['edit'])) {
    $room_id = $_POST['room_id'];

    // ดึงข้อมูลห้องจากฐานข้อมูล
    $sql = $conn->prepare("SELECT * FROM room WHERE room_id = :room_id");
    $sql->bindParam(':room_id', $room_id);
    $sql->execute();
    $room = $sql->fetch(PDO::FETCH_ASSOC);
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

    <title>แก้ไขข้อมูลห้อง</title>

    <link rel="stylesheet" href="css/adm-editr.css">
    <!-- <link rel="stylesheet" href="css/adeditr.css"> -->

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

    <!-- ==== All Body Content ==== -->
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
            <h2>แก้ไขข้อมูลห้อง</h2>
            <form action="crudroom_db.php" method="POST">
                <div class="detail">
                    <input type="hidden" name="room_id" value="<?php echo htmlspecialchars($room['room_id']); ?>">

                    <label for="room_name">ชื่อห้อง:</label>
                    <input type="text" id="room_name" name="room_name" value="<?php echo htmlspecialchars($room['room_name']); ?>" required>
                </div>
                <div class="detail-1">
                    <label for="room_status">สถานะห้อง:</label>
                    <select id="room_status" name="room_status" required>
                        <option value="1" <?php echo $room['room_status'] == 1 ? 'selected' : ''; ?>>พร้อมใช้งาน</option>
                        <option value="0" <?php echo $room['room_status'] == 0 ? 'selected' : ''; ?>>ไม่พร้อมใช้งาน</option>
                    </select>
                </div>
                <button type="submit" name="edit">บันทึก</button>
            </form>
        </div>
    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>