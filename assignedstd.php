<?php

session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['student_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}
$student_login = $_SESSION['student_login'];
$sql = "SELECT m.member_id, m.member_name, m.member_lastname, m.member_gender, 
                    sr.service_request_id,sr.service_request_date, 
                    sr.service_request_time, sr.service_request_data, 
                    sr.service_value_data, srt.servicerequest_type_name,
                    ss.shift_id, ss.shift_name, ss.shift_time_in, ss.shift_time_out, 
                    mm.member_name AS tname,
                    mm.member_lastname AS tlastname
                FROM service_request sr
                JOIN member m ON m.member_id = sr.member_id_request
                JOIN service_request_type srt ON srt.servicerequest_type_id = sr.servicerequest_type_id
                JOIN shift_schedule ss ON ss.shift_id = sr.shift_id
                JOIN member mm ON mm.member_id = ss.member_id_teacher
                WHERE sr.member_id_student = :student_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':student_id', $student_login);
$stmt->execute();
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีข้อมูลและคีย์ shift_name หรือไม่
if (isset($rows[0]['shift_name'])) {
    $day_name = $rows[0]['shift_name']; // ดึงข้อมูล shift_name จากแถวแรก
} else {
    $day_name = 'ไม่พบข้อมูลเวรประจำวัน'; // ตั้งค่าด้วยข้อความที่แสดงเมื่อไม่พบข้อมูล
}
$day_namethai = '';
switch ($day_name) {
    case 'Mondaymorning':
        $day_namethai = 'จันทร์';
        break;
    case 'Mondayafternoon':
        $day_namethai = 'จันทร์';
        break;
    case 'Tuesdaymorning':
        $day_namethai = 'อังคาร';
        break;
    case 'Tuesdayafternoon':
        $day_namethai = 'อังคาร';
        break;
    case 'Wednesdaymorning':
        $day_namethai = 'พุธ';
        break;
    case 'Wednesdayafternoon':
        $day_namethai = 'พุธ';
        break;
    case 'Thursdaymorning':
        $day_namethai = 'พฤหัสบดี';
        break;
    case 'Thursdayafternoon':
        $day_namethai = 'พฤหัสบดี';
        break;
    case 'Fridaymorning':
        $day_namethai = 'ศุกร์';
        break;
    case 'Fridayafternoon':
        $day_namethai = 'ศุกร์';
        break;
    default:
        $day_namethai = $day_name;
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

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="css/styleassignedstd.css"> -->

    <link rel="stylesheet" href="css/aasignstd.css">

    <title>Student assignedstd</title>
</head>

<body>

    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-std.php");
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
            <h2>งานที่ได้รับมอบหมาย</h2>

            <?php if (!empty($rows)) : ?>
                <h4>เวรประจำวัน<?= htmlspecialchars($day_namethai) . " เวลา : " . htmlspecialchars($rows[0]['shift_time_in']) . " - " . htmlspecialchars($rows[0]['shift_time_out']) ?></h4>
                <h4>อาจารย์เวรประจำวัน <?= htmlspecialchars($rows[0]['tname']) . " " . htmlspecialchars($rows[0]['tlastname']) ?></h4>

                <table>
                    <tr>
                        <th>รหัสคำขอ</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>เพศ</th>
                        <th>วันที่ขอรับบริการ</th>
                        <th>เวลา</th>
                        <th>เรื่องที่ต้องการขอรับบริการ</th>
                    </tr>
                    <?php foreach ($rows as $row) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['service_request_id']) ?></td>
                            <td><?= htmlspecialchars($row['member_name']) . " " . htmlspecialchars($row['member_lastname']) ?></td>
                            <td><?= htmlspecialchars($row['member_gender']) ?></td>
                            <td><?= htmlspecialchars($row['service_request_date']) ?></td>
                            <td><?= htmlspecialchars($row['service_request_time']) ?></td>
                            <td><?= htmlspecialchars($row['servicerequest_type_name']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else : ?>
                <h3>ไม่พบข้อมูลตารางเวร</h3>
                <table>
                    <tr>
                        <th>รหัสคำขอ</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>เพศ</th>
                        <th>วันที่ขอรับบริการ</th>
                        <th>เวลา</th>
                        <th>เรื่องที่ต้องการขอรับบริการ</th>
                    </tr>
                </table>
            <?php endif; ?>
        </div>
    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>