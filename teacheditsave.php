<?php
session_start();
require_once 'config/conndb.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบ
if (!isset($_SESSION['teacher_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

// ตรวจสอบว่า `service_request_id` ถูกส่งมาหรือไม่
if (!isset($_GET['service_request_id'])) {
    $_SESSION['error'] = 'ไม่พบข้อมูลคำขอ';
    header("Location: teachshowsave.php");
    exit();
}

$service_request_id = $_GET['service_request_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // ดึงข้อมูลจากฟอร์ม
    $dataconsult = $_POST['dataconsult'];
    $valueconsult = $_POST['valueconsult'];

    // อัปเดตข้อมูลในฐานข้อมูล
    $update_sql = "UPDATE service_request
SET service_request_data = :dataconsult, service_value_data = :valueconsult
WHERE service_request_id = :service_request_id";
    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':dataconsult', $dataconsult);
    $stmt->bindParam(':valueconsult', $valueconsult);
    $stmt->bindParam(':service_request_id', $service_request_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว';
        header("Location: teachshowsave.php");
        exit();
    } else {
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
    }
} else {
    // ดึงข้อมูลที่มีอยู่จากฐานข้อมูล
    $sql = "SELECT sr.service_request_id, sr.service_request_data, 
    sr.service_value_data, m.member_name, m.member_lastname, 
    m.member_gender, sr.service_request_date, sr.service_request_time,
    srt.servicerequest_type_name
FROM service_request sr
JOIN member m ON m.member_id = sr.member_id_request
JOIN service_request_type srt ON srt.servicerequest_type_id = sr.servicerequest_type_id
WHERE sr.service_request_id = :service_request_id";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':service_request_id', $service_request_id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$data) {
        $_SESSION['error'] = 'ไม่พบข้อมูลคำขอนี้';
        header("Location: teachshowsave.php");
        exit();
    }
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

    <link rel="stylesheet" href="css/tedit-sho.css">

    <title>Edit Request</title>
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
                <a href="teachshowsave.php" class="bi bi-arrow-left" id="BackBtn"></a>
                <h2>แก้ไขข้อมูลคำขอ</h2>
            </div>
            <?php if (isset($_SESSION['error'])) { ?>
                <div class="alert alert-danger">
                    <?php
                    echo $_SESSION['error'];
                    unset($_SESSION['error']);
                    ?>
                </div>
            <?php } ?>
            <?php if (isset($_SESSION['success'])) { ?>
                <div class="alert alert-success">
                    <?php
                    echo $_SESSION['success'];
                    unset($_SESSION['success']);
                    ?>
                </div>
            <?php } ?>

            <form action="?service_request_id=<?php echo htmlspecialchars($service_request_id); ?>" method="post">
                <div class="form-group">
                    <label>ชื่อ - นามสกุล :</label>
                    <span class="info"><?php echo htmlspecialchars($data['member_name']) . " " . htmlspecialchars($data['member_lastname']); ?></span>
                </div>
                <div class="form-group">
                    <label>เพศ :</label>
                    <span class="info"><?php echo htmlspecialchars($data['member_gender']); ?></span>
                </div>
                <div class="form-group">
                    <label>วันที่ :</label>
                    <span class="info"><?php echo htmlspecialchars($data['service_request_date']); ?></span>
                </div>
                <div class="form-group">
                    <label>เวลา :</label>
                    <span class="info"><?php echo htmlspecialchars($data['service_request_time']); ?></span>
                </div>
                <div class="form-group">
                    <label>เรื่องที่ต้องการขอรับบริการ :</label>
                    <span class="info"><?php echo htmlspecialchars($data['servicerequest_type_name']); ?></span>
                </div>
                <div class="form-group">
                    <label for="dataconsult">ข้อมูลการให้การปรึกษา :</label>
                    <textarea name="dataconsult" rows="4"><?php echo htmlspecialchars($data['service_request_data']); ?></textarea>
                </div>
                <div class="form-group">
                    <label for="valueconsult">ผลการให้การปรึกษา :</label>
                    <textarea name="valueconsult" rows="4"><?php echo htmlspecialchars($data['service_value_data']); ?></textarea>
                </div>
                <button type="submit">อัปเดตข้อมูล</button>
            </form>

        </div>
    </main>
</body>

</html>