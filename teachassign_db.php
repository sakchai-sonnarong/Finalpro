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
    header("Location: teachassign.php");
    exit();
}

$service_request_id = $_GET['service_request_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $memberidstudent = $_POST['member_id_student'];

    $update_sql = "UPDATE service_request
                SET member_id_student = :memberidstudent
                WHERE service_request_id = :serviceid";

    $stmt = $conn->prepare($update_sql);
    $stmt->bindParam(':memberidstudent', $memberidstudent);
    $stmt->bindParam(':serviceid', $service_request_id);

    if ($stmt->execute()) {
        $_SESSION['success'] = 'ข้อมูลถูกอัปเดตเรียบร้อยแล้ว';
        header("Location: teachassign.php");
        exit();
    } else {
        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการอัปเดตข้อมูล';
    }
} else {
    $sql = "SELECT sr.service_request_id, m.member_name, 
                m.member_lastname, srt.servicerequest_type_name,
                sr.service_request_date, sr.service_request_time,
                mst.member_id AS member_student_id,
                mst.member_name AS member_student_name,
                mst.member_lastname AS member_student_lastname
            FROM service_request sr
            JOIN member m ON sr.member_id_request = m.member_id
            JOIN shift_student_assignment ssa ON sr.shift_id = ssa.shift_id
            JOIN member mst ON ssa.member_student_id = mst.member_id
            JOIN service_request_type srt ON sr.servicerequest_type_id = srt.servicerequest_type_id
            WHERE sr.service_request_id = :serviceid";

    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':serviceid', $service_request_id);
    $stmt->execute();
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    $std_sql = "SELECT m.member_id, m.member_name, m.member_lastname,mt.member_id AS tid,mt.member_name AS tname, mt.member_lastname AS tlastname
                FROM service_request sr
                JOIN shift_student_assignment ssa ON sr.shift_id = ssa.shift_id
                JOIN shift_schedule ss ON sr.shift_id = ss.shift_id
                JOIN member mt ON ss.member_id_teacher = mt.member_id
                JOIN member m ON ssa.member_student_id = m.member_id
                WHERE sr.service_request_id = :serviceid
                ORDER BY tid";
    $std_stmt = $conn->prepare($std_sql);
    $std_stmt->bindParam(':serviceid', $service_request_id);
    $std_stmt->execute();
    $stds = $std_stmt->fetchAll(PDO::FETCH_ASSOC);
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

    <link rel="stylesheet" href="css/eassign-t.css">

    <title>Assign</title>
</head>

<body>
    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-teacher.php");
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
            <h2>มอบหมายงาน</h2>
            <form action="" method="post">
                <div class="line">
                    <input type="hidden" name="service_request_id" value="<?php echo htmlspecialchars($data['service_request_id']); ?>">
                    <label for="member_name">ชื่อผู้ขอรับบริการ :</label>
                    <?php echo htmlspecialchars($data['member_name']) . " " . htmlspecialchars($data['member_lastname']); ?>
                </div>
                <div class="line">
                    <label for="servicerequest_type_name">เรื่องที่ต้องการขอรับบริการ :</label>
                    <?php echo htmlspecialchars($data['servicerequest_type_name']); ?>
                </div>
                <div class="line">
                    <label for="service_request_date">วันที่ขอรับบริการ :</label>
                    <?php echo htmlspecialchars($data['service_request_date']) . " " . "เวลา :" . htmlspecialchars($data['service_request_time']); ?>
                </div>
                <div class="line">
                    <label for="member_id_student">มอบหมายให้</label>
                    <select name="member_id_student" id="member_id_student">
                        <option selected disabled value="">เลือกผู้ให้บริการ</option>
                        <?php
                        $shown_student_ids = [];
                        foreach ($stds as $row) {
                            if (!in_array($row['member_id'], $shown_student_ids)) {
                                $shown_student_ids[] = $row['member_id']; // เก็บ member_id ที่แสดงไปแล้ว
                        ?>
                                <option value="<?php echo $row['member_id']; ?>"><?php echo $row['member_name'] . " " . $row['member_lastname']; ?></option>
                        <?php
                            }
                        } ?>
                        <?php
                        $shown_teacher_ids = [];
                        foreach ($stds as $row) {
                            if (!in_array($row['tid'], $shown_teacher_ids)) {
                                $shown_teacher_ids[] = $row['tid']; // เก็บ tid ที่แสดงไปแล้ว
                        ?>
                                <option value="<?php echo $row['tid']; ?>"><?php echo $row['tname'] . " " . $row['tlastname']; ?></option>
                        <?php
                            }
                        } ?>
                    </select>
                </div>
                <button type="submit">บันทึก</button>

            </form>
        </div>
    </main>
</body>

</html>