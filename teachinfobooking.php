<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['teacher_login'])) {
    // ถ้ายังไม่ได้ล็อกอิน ให้ redirect ไปหน้า login
    header("Location: index.php");
    exit();
}

// ดึงข้อมูลจากฐานข้อมูล
$member_id = $_SESSION['teacher_login'];
$sql = "SELECT b.booking_id, m.member_name, m.member_lastname, r.room_name, b.booking_date, b.booking_time_in, b.booking_time_out, b.status
    FROM booking_room b
    JOIN member m ON b.member_id = m.member_id
    JOIN room r ON b.room_id = r.room_id
    WHERE b.member_id = :member_id";
$stmt = $conn->prepare($sql);
$stmt->bindParam(':member_id', $member_id);
$stmt->execute();
$bookings = $stmt->fetchAll(PDO::FETCH_ASSOC);

date_default_timezone_set('Asia/Bangkok');

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
    <!-- <link rel="stylesheet" href="css/teachinfobooking.css"> -->
    <link rel="stylesheet" href="css/tinfobkr.css">

    <title>Teacher InforBooking room</title>
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
            <h2>ข้อมูลการจองห้อง</h2>
            <table>
                <tr>
                    <th>ชื่อ - สกุล</th>
                    <th>ห้อง</th>
                    <th>วันที่</th>
                    <th>เวลา</th>
                    <th>สถานะการจอง</th>
                    <th>**หมายเหตุ**</th>
                </tr>

                <?php
                // แสดงข้อมูลในตาราง HTML
                foreach ($bookings as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["member_name"]) . " " . htmlspecialchars($row["member_lastname"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["room_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["booking_date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["booking_time_in"]) . " - " . htmlspecialchars($row["booking_time_out"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["status"]) . "</td>";
                    echo "<td>";

                    // echo "<form method='POST' action='teachshowmybookingroom_db.php' style='display:inline-block;'>";
                    echo "<form method='POST' action='teachshowmybookingroom_db.php'>";
                    echo "<input type='hidden' name='booking_id' value='" . htmlspecialchars($row["booking_id"]) . "'>";

                    // สร้างตัวแปรวันที่และเวลาปัจจุบัน
                    $currentDateTime = new DateTime(); // เวลาปัจจุบัน
                    $currentDateTimeFormatted = $currentDateTime->format('Y-m-d H:i:s'); // แปลงเป็น string เพื่อตรวจสอบ

                    // สร้างตัวแปรวันที่และเวลาจากฐานข้อมูล
                    $bookingDateTime = new DateTime($row["booking_date"] . ' ' . $row["booking_time_in"]); // วันที่และเวลาในฐานข้อมูล
                    $bookingDateTimeFormatted = $bookingDateTime->format('Y-m-d H:i:s'); // แปลงเป็น string เพื่อตรวจสอบ

                    // Debug: ดูค่าว่าเป็นไปตามที่คาดหรือไม่
                    // echo "Current DateTime: " . $currentDateTimeFormatted . "<br>";
                    // echo "Booking DateTime: " . $bookingDateTimeFormatted . "<br>";

                    // เปรียบเทียบสถานะและวันที่เวลา
                    if ($row["status"] === "จองแล้ว") {
                        // เปรียบเทียบเวลาปัจจุบันกับเวลาที่กำหนดในฐานข้อมูล
                        if ($currentDateTime >= $bookingDateTime) {
                            // ถ้าถึงเวลาที่กำหนดแล้วให้ปุ่มพร้อมใช้งาน
                            echo "<button id='approveButton" . htmlspecialchars($row["booking_id"]) . "' onclick='hideButton(\"approveButton" . htmlspecialchars($row["booking_id"]) . "\")' type='submit' name='status' value='กำลังใช้งาน'>ยืนยันการเข้าใช้ห้อง</button>";
                        } else {
                            // ถ้าไม่ถึงเวลาที่กำหนดให้ปิดการใช้งานปุ่ม
                            echo "<button disabled style='background-color: light grey; color: black;'>ยังไม่ถึงเวลาที่กำหนด</button>";
                        }
                        echo "<button type='submit2' name='action' value='delete' onclick='return confirm(\"คุณต้องการลบการจองนี้หรือไม่?\");'>ยกเลิกการจอง</button>";
                    } elseif ($row["status"] === "กำลังใช้งาน") {
                        echo "<button id='succeedButton" . htmlspecialchars($row["booking_id"]) . "' onclick='hideButton(\"succeedButton" . htmlspecialchars($row["booking_id"]) . "\")' type='submit' name='status' value='ใช้งานเสร็จสิ้น'>ใช้ห้องเสร็จสิ้น</button>";
                    }
                    echo "</form>";
                    echo "</td>";
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