<?php

session_start();
require_once 'config/conndb.php';

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบ
if (!isset($_SESSION['teacher_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}
$teach_id = $_SESSION['teacher_login'];
$sql = "SELECT sr.service_request_id, 
            sr.service_request_date, 
            sr.service_request_time, 
            sr.member_id_student, 
            m.member_name AS requester_name, 
            m.member_lastname AS requester_lastname,
            ms.member_name AS student_name, 
            ms.member_lastname AS student_lastname
        FROM service_request sr
        JOIN shift_schedule ss ON sr.shift_id = ss.shift_id
        JOIN member m ON sr.member_id_request = m.member_id
        LEFT JOIN member ms ON sr.member_id_student = ms.member_id
        WHERE ss.member_id_teacher = :teach_id AND sr.service_request_status = 1";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':teach_id', $teach_id);
$stmt->execute();
$request = $stmt->fetchAll(PDO::FETCH_ASSOC);

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
    <!-- <link rel="stylesheet" href="css/teachassign.css"> -->
    <link rel="stylesheet" href="css/taasign.css">

    <title>Teacher assignedstd</title>
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
            <table>
                <tr>
                    <th>รหัสคำขอ</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>วันที่ขอรับบริการ</th>
                    <th>เวลา</th>
                    <th>มอบหมายงาน</th>
                </tr>
                <?php
                // Sample PHP code to fetch data and display in the table
                foreach ($request as $row) {
                    $serviceRequestId = htmlspecialchars($row["service_request_id"]);
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["service_request_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["requester_name"]) . " " . htmlspecialchars($row["requester_lastname"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["service_request_date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["service_request_time"]) . "</td>";
                    echo "<td><a href='teachassign_db.php?service_request_id=$serviceRequestId'>" . (empty($row["member_id_student"]) ? "มอบหมายให้" : htmlspecialchars($row["student_name"])) . " " . htmlspecialchars($row["student_lastname"]) . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
        </div>
    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>