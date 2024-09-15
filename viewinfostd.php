<?php

session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['student_login'])) {
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

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="css/std-viewinfo.css"> -->
    <link rel="stylesheet" href="css/viewinfo-std.css">

    <title>Booking-Room Student</title>

    <script>
        function showAlert(memberid, membername, memberlastname, membergender, servicedate, servicetime, servicedata, servicerequestid, servicevalue, servicerequestname) {
            let message = "ต้องการแก้ไขข้อมูลนี้หรือไม่?\n\n";
            message += "รหัสคำขอ : " + servicerequestid + "  ผู้ขอรับบริการ : " + membername + " " + memberlastname +
                "\nเพศ : " + membergender + "\nวันที่ : " + servicedate + "\nเวลา : " + servicetime + "\nเรื่องที่ขอเข้ารับบริการ : " + servicerequestname +
                "\nข้อมูลการให้การปรึกษา : " + servicedata +
                "\nผลการให้การปรึกษา : " + servicevalue;

            // แสดงข้อความในกล่องข้อความที่กำหนดเอง
            document.getElementById("alertMessage").innerText = message;
            document.getElementById("customAlert").style.display = "block";

            // เก็บค่า servicerequestid ไว้ในตัวแปร global
            window.servicerequestid = servicerequestid; // เพิ่มบรรทัดนี้เพื่อกำหนดค่า servicerequestid
        }

        // ฟังก์ชันเมื่อผู้ใช้กด "แก้ไข"
        function handleEdit() {
            // let servicerequestid = ...; 
            window.location.href = "editinfostd.php?service_request_id=" + window.servicerequestid;
        }

        // ฟังก์ชันเพื่อปิดกล่องข้อความ
        function closeAlert() {
            document.getElementById("customAlert").style.display = "none";
        }
    </script>

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
            <h2>ประวัติข้อมูลการให้การปรึกษา</h2>
            <table>
                <tr>
                    <!-- <td>รหัสคำขอ</td>
                    <td>ชื่อ - นามสกุล</td>
                    <td>เพศ</td>
                    <td>วันที่</td>
                    <td>เวลา</td> -->
                    <th>รหัสคำขอ</th>
                    <th>ชื่อ - นามสกุล</th>
                    <th>เพศ</th>
                    <th>วันที่</th>
                    <th>เวลา</th>
                </tr>
                <?php
                $student_login = $_SESSION['student_login'];
                $sql = "SELECT m.member_id, m.member_name, m.member_lastname, m.member_gender, 
                            sr.service_request_id,sr.service_request_date, sr.service_request_time, 
                            sr.service_request_data, sr.service_value_data, srt.servicerequest_type_name
                FROM service_request sr
                JOIN member m ON m.member_id = sr.member_id_request
                JOIN service_request_type srt ON srt.servicerequest_type_id = sr.servicerequest_type_id
                WHERE sr.member_id_student = :student_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':student_id', $student_login);
                $stmt->execute();
                $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($rows as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row['service_request_id']) . "</td>";
                    echo "<td><a href='#' onclick='showAlert("
                        . json_encode($row["member_id"]) . ", "
                        . json_encode($row["member_name"]) . ", "
                        . json_encode($row["member_lastname"]) . ", "
                        . json_encode($row["member_gender"]) . ", "
                        . json_encode($row["service_request_date"]) . ", "
                        . json_encode($row["service_request_time"]) . ", "
                        . json_encode($row["service_request_data"]) . ", "
                        . json_encode($row["service_request_id"]) . ", "
                        . json_encode($row["service_value_data"]) . "," 
                        . json_encode($row["servicerequest_type_name"]) . ")'>"
                        . htmlspecialchars($row["member_name"]) . " "
                        . htmlspecialchars($row["member_lastname"]) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row['member_gender']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['service_request_date']) . "</td>";
                    echo "<td>" . htmlspecialchars($row['service_request_time']) . "</td>";
                    echo "</tr>";
                }

                ?>
            </table>

            <div id="customAlert" class="custom-alert">
                <div class="custom-alert-content">
                    <p id="alertMessage"></p>
                    <button type="submit" onclick="handleEdit()">แก้ไข</button>
                    <!-- <button onclick="handleView()">ตกลง</button> -->
                    <button type="submit2" onclick="closeAlert()"><i class="bi bi-x-lg"></i></button>
                </div>
            </div>

        </div>

    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>