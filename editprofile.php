<?php

session_start();
require_once 'config/conndb.php';

// ตรวจสอบว่าผู้ใช้งานเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['admin_login']) && !isset($_SESSION['teacher_login']) && !isset($_SESSION['student_login']) && !isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}
// รับค่า member_id จากฟอร์ม (หากมี)
$member_id = isset($_POST['member_id']) ? $_POST['member_id'] : null;

// หากไม่มี member_id จากฟอร์ม ให้รับจาก session error (หากมี)
if (!$member_id && isset($_SESSION['error_member_id'])) {
    $member_id = $_SESSION['error_member_id'];
    unset($_SESSION['error_member_id']); // ลบค่าออกจาก session หลังการใช้งาน
}

// หากไม่มี member_id จากฟอร์มหรือ session error ให้ใช้ค่า member_id จาก session login
if (!$member_id) {
    $member_id = isset($_SESSION['admin_login']) ? $_SESSION['admin_login'] : (isset($_SESSION['teacher_login']) ? $_SESSION['teacher_login'] : (isset($_SESSION['student_login']) ? $_SESSION['student_login'] : (isset($_SESSION['user_login']) ? $_SESSION['user_login'] : null)));
}

// ตรวจสอบว่าได้ค่า member_id มาหรือไม่
if ($member_id) {
    $sql = "SELECT * 
            FROM member m
            JOIN member_type mt 
            ON m.member_type_id = mt.member_type_id
            WHERE m.member_id = :memberid";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':memberid', $member_id);
    $stmt->execute();
    $member = $stmt->fetch(PDO::FETCH_ASSOC);

    // ตรวจสอบกรณีที่ไม่พบข้อมูลสมาชิก
    if (!$member) {
        $_SESSION['error'] = 'ไม่พบข้อมูลสมาชิก';
        header("Location: profile.php");
        exit();
    }
} else {
    $_SESSION['error'] = 'ไม่พบข้อมูลสมาชิก';
    header("Location: profile.php");
    exit();
}


if (isset($_SESSION['admin_login'])) {
    $homeUrl = 'profile.php';
} elseif (isset($_SESSION['teacher_login'])) {
    $homeUrl = 'profile.php';
} elseif (isset($_SESSION['student_login'])) {
    $homeUrl = 'profile.php';
} elseif (isset($_SESSION['user_login'])) {
    $homeUrl = 'profile.php';
} else {
    $homeUrl = 'index.php'; // Fallback in case something goes wrong
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

    <link rel="stylesheet" href="css/eprofile.css">

    <title>EditProfile</title>
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

    <!--  Container  -->
    <main>
        <div class="container">
            <div class="head">
                <a href="<?php echo htmlspecialchars($homeUrl); ?>" class="bi bi-arrow-left" id="BackBtn"></a>
                <h2>ข้อมูลของฉัน</h2>
            </div>
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
            <form action="editprofile_db.php" method="post">
                <input type="hidden" name="member_id" value="<?php echo htmlspecialchars($member['member_id']); ?>">
                <div class="detail">
                    <label for="name">ชื่อ :</label>
                    <textarea id="member_name" name="member_name"><?php echo htmlspecialchars($member['member_name']); ?></textarea>
                </div>
                <div class="detail">
                    <label for="lastname">นามสกุล :</label>
                    <textarea id="member_lastname" name="member_lastname"><?php echo htmlspecialchars($member['member_lastname']); ?></textarea>
                </div>
                <div class="detail">
                    <label for="age">อายุ :</label>
                    <textarea id="member_age" name="member_age"><?php echo htmlspecialchars($member['member_age']); ?></textarea>
                </div>
                <div class="detail">
                <label for="gender">เพศ :</label>
                    <select id="member_gender" name="member_gender">
                        <option value="ชาย" <?php echo $member['member_gender'] == 'ชาย' ? 'selected' : ''; ?>>ชาย</option>
                        <option value="หญิง" <?php echo $member['member_gender'] == 'หญิง' ? 'selected' : ''; ?>>หญิง</option>
                        <option value="LGBTQ" <?php echo $member['member_gender'] == 'LGBTQ' ? 'selected' : ''; ?>>LGBTQ</option>
                    </select>
                </div>
                <div class="detail">
                    <label for="member_phone">เบอร์โทร :</label>
                    <textarea id="member_phone" name="member_phone"><?php echo htmlspecialchars($member['member_phone']); ?></textarea>
                </div>
                <div class="detail">
                    <label for="member_email">อีเมล :</label>
                    <textarea id="member_email" name="member_email"><?php echo htmlspecialchars($member['member_email']); ?></textarea>
                </div>
                    <button type="submit" name="submit">บันทึก</button>

            </form>
        </div>
    </main>


</body>

</html>