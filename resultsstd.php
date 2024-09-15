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
    <!-- <link rel="stylesheet" href="css/stdresuls.css"> -->
    <link rel="stylesheet" href="css/std-resuls.css">

    <title>Student results</title>

    <script>
        function showAlert(memberId, membername, memberlastname, servicedate, servicestatus, servicedetail, note, servicetime) {
            let statusText;
            switch (servicestatus) {
                case 0:
                    statusText = "รอนุมัติ";
                    break;
                case 1:
                    statusText = "อนุมัติ";
                    break;
                case 2:
                    statusText = "ไม่อนุมัติ";
                    break;
                default:
                    statusText = "ไม่ทราบสถานะ";
                    break;
            }

            let alertMessage = "วันที่ขอรับบริการ : " + servicedate + " " + "เวลา : " + servicetime +
                "\nMember ID : " + memberId +
                "\nชื่อ : " + membername + " " + memberlastname +
                "\nสถานะคำขอ : " + statusText +
                "\nรายละเอียดการขอรับบริการบริการ : " + servicedetail +
                "\nหมายเหตุ : " + note;

            document.getElementById("alertText").innerText = alertMessage;
            document.getElementById("customAlert").style.display = "block"; // แสดง modal
        }

        function closeAlert() {
            document.getElementById("customAlert").style.display = "none"; // ปิด modal
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
            <h3>ผลคำขอเข้ารับบริการ</h3>
            <table>
                <tr>
                    <!-- <td>ชื่อ-นามสกุล</td>
                    <td>วันที่ขอรับบริการ</td>
                    <td>เวลา</td>
                    <td>ผลการตอบรับ</td>
                    <td>หมายเหตุ</td> -->
                    <th>ชื่อ-นามสกุล</th>
                    <th>วันที่ขอรับบริการ</th>
                    <th>เวลา</th>
                    <th>ผลการตอบรับ</th>
                    <th>หมายเหตุ</th>
                </tr>
                <?php
                $student_id = $_SESSION['student_login'];
                $sql = "SELECT m.member_id, m.member_name, m.member_lastname, sr.service_request_Basicdetails, sr.service_request_date,sr.service_request_time , sr.service_request_status, sr.note
                FROM member m
                JOIN service_request sr
                ON m.member_id = sr.member_id_request
                WHERE sr.member_id_request = :student_id";
                $stmt = $conn->prepare($sql);
                $stmt->bindParam(':student_id', $student_id);
                $stmt->execute();
                $myrequest = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($myrequest as $row) {
                    echo "<tr>";
                    echo "<td><a href='#' onclick='showAlert("
                        . $row["member_id"] . ", \""
                        . htmlspecialchars($row["member_name"]) . "\", \""
                        . htmlspecialchars($row["member_lastname"]) . "\", \""
                        . htmlspecialchars($row["service_request_date"]) . "\", "
                        . $row["service_request_status"] . ", \""
                        . htmlspecialchars($row["service_request_Basicdetails"]) . "\", \""
                        . htmlspecialchars($row["note"]) . "\", \""
                        . htmlspecialchars($row["service_request_time"]) . "\")'>"
                        . htmlspecialchars($row["member_name"]) . " "
                        . htmlspecialchars($row["member_lastname"]) . "</a></td>";
                    echo "<td>" . htmlspecialchars($row["service_request_date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["service_request_time"]) . "</td>";
                    echo "<td>";
                    switch ($row["service_request_status"]) {
                        case 0:
                            echo "รอนุมัติ";
                            break;
                        case 1:
                            echo "อนุมัติ";
                            break;
                        case 2:
                            echo "ไม่อนุมัติ";
                            break;
                        default:
                            echo "ไม่ทราบสถานะ";
                            break;
                    }
                    echo "</td>";
                    echo "<td>" . (empty($row["note"]) ? "-" : htmlspecialchars($row["note"])) . "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
            <h2>**คลิกที่ชื่อเพื่อดูรายละเอียด**</h2>

            <div id="customAlert" class="custom-alert">
                <div class="custom-alert-content">
                    <button class="btn" onclick="closeAlert()" aria-label="ปิดข้อความแจ้งเตือน"><i class="bi bi-x-lg"></i></button>
                    <p id="alertText"></p>
                </div>
            </div>

        </div>
    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>