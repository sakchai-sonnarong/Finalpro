<?php
session_start();
require_once 'config/conndb.php';
if (!isset($_SESSION['teacher_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
}

// ตรวจสอบและกำหนดค่าห้องและวันที่ที่ถูกเลือกไว้ในเซสชัน
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $_SESSION['selectedRoom'] = $_POST['selectroom'];
    $_SESSION['selectedDate'] = $_POST['date'];
}

$selectedRoom = isset($_SESSION['selectedRoom']) ? $_SESSION['selectedRoom'] : '';
$selectedDate = isset($_SESSION['selectedDate']) ? $_SESSION['selectedDate'] : '';

$result = json_decode($_GET['result'] ?? '', true) ?? [];
$timeSlots = json_decode($_GET['timeSlots'] ?? '', true) ?? [];
$room = htmlspecialchars($_GET['room'] ?? '', ENT_QUOTES, 'UTF-8');
$date = htmlspecialchars($_GET['date'] ?? '', ENT_QUOTES, 'UTF-8');
$date = !empty($date) ? $date : date('Y-m-d');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- <link rel="stylesheet" href="css/teachbooking.css"> -->
    <link rel="stylesheet" href="css/t-showbr.css">

    <title>Teacher Bookingroom</title>

    <script>
        function bookRoom(startTime, endTime, room, date) {

            // ตรวจสอบสถานะห้องก่อนดำเนินการ
            const roomStatus = document.querySelector(`tr[data-room="${room}"]`).dataset.status; //Enclosed the selector in backticks (``) to allow proper string interpolation.
            const currentTime = new Date();
            const bookingDate = new Date(date);

            if (roomStatus === '0') {
                alert('ห้องไม่พร้อมใช้งาน');
                return;
            }

            // ตรวจสอบว่าเวลาที่เลือกไม่เกินช่วงเวลาที่สามารถจองได้
            const selectedStartTime = new Date(`${date}T${startTime}:00`); //Used backticks (``) for proper string interpolation.
            const selectedEndTime = new Date(`${date}T${endTime}:00`);

            if (selectedStartTime < currentTime) {
                alert('เวลาที่เลือกไม่สามารถจองได้');
                return;
            }

            const form = document.createElement('form');
            form.method = 'POST';
            form.action = 'teachbookingroom.php';

            const startInput = document.createElement('input');
            startInput.type = 'hidden';
            startInput.name = 'startTime';
            startInput.value = startTime;
            form.appendChild(startInput);

            const endInput = document.createElement('input');
            endInput.type = 'hidden';
            endInput.name = 'endTime';
            endInput.value = endTime;
            form.appendChild(endInput);

            const roomInput = document.createElement('input');
            roomInput.type = 'hidden';
            roomInput.name = 'room';
            roomInput.value = room;
            form.appendChild(roomInput);

            const dateInput = document.createElement('input');
            dateInput.type = 'hidden';
            dateInput.name = 'date';
            dateInput.value = date;
            form.appendChild(dateInput);

            document.body.appendChild(form);
            form.submit();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const clickableRows = document.querySelectorAll('.clickable');
            clickableRows.forEach(row => {
                row.addEventListener('click', function() {
                    const startTime = this.dataset.time;
                    const [hours, minutes] = startTime.split(':');
                    const endHours = String(Number(hours) + 1).padStart(2, '0');
                    const endTime = `${endHours}:${minutes}`;
                    const room = this.dataset.room;
                    const date = this.dataset.date;
                    bookRoom(startTime, endTime, room, date);
                });
            });
        });
    </script>
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

        <div class="mb-3">
            <?php

            $selectroom = $conn->prepare('SELECT * FROM room');
            $selectroom->execute();

            ?>
        </div>
        <div id="container">
            <div class="title">
                <h3>การจองห้องให้การปรึกษา</h3>
                <?php if (isset($_SESSION['error'])) { ?>
                    <div class="alert alert-danger" role="alert">
                        <?php
                        echo $_SESSION['error'];
                        unset($_SESSION['error']);
                        ?>
                    </div>
                <?php } ?>
                <form action="teachshowbookingroom_db.php" method="POST">
                    <label for="roomselect">ห้องศูนย์ให้การปรึกษา :
                        <select name="selectroom" id="room_id">
                            <option selected disabled value=""><?= $room ?> </option>
                            <?php while ($rooms = $selectroom->FETCH(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $rooms['room_name'] ?>" <?php echo ($rooms['room_name'] == $selectedRoom) ? 'selected' : ''; ?>><?php echo $rooms['room_name'] ?></option>
                            <?php } ?>
                        </select>
                    </label>
                    <label for="date">วันที่ :
                        <input type="date" id="date" name="date" value="<?php echo date('Y-m-d', strtotime($date)); ?>" min="<?php echo date('Y-m-d'); ?>">
                    </label>
                    <button type="submit" name="search">ตรวจสอบ</button>
                </form>
            </div>

            <div id="timetable">

                <?php if (isset($_GET['result'])) : ?>

                    <?php if (count($result) > 0) : ?>

                        <table>
                            <tr>
                                <th>เวลา</th>
                                <th>ผู้จอง</th>
                                <th>สถานะ</th>
                            </tr>
                            <?php foreach ($timeSlots as $time => $status) : ?>
                                <?php
                                $clickable = $status == "ว่าง" ? "clickable" : "";
                                $statusText = $status;
                                $bookingName = $status == "ว่าง" ? '-' : ''; // Default value

                                foreach ($result as $row) {
                                    $bookingStart = $row["booking_time_in"];
                                    $bookingEnd = $row["booking_time_out"];
                                    if ($time >= date("H:i", strtotime($bookingStart)) && $time < date("H:i", strtotime($bookingEnd))) {
                                        $bookingName = $row['member_name'] . ' ' . $row['member_lastname'];
                                        break;
                                    }
                                }
                                ?>
                                <tr class="<?= $clickable ?>" data-time="<?= $time ?>" data-room="<?= $room ?>" data-date="<?= $date ?>">
                                    <td><?= $time ?> - <?= date("H:i", strtotime("+1 hour", strtotime($time))) ?></td>
                                    <td><?= $bookingName ?></td>
                                    <td><?= $statusText ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php else : ?>
                        <table>
                            <tr>
                                <th>เวลา</th>
                                <th>ผู้จอง</th>
                                <th>สถานะ</th>
                            </tr>
                            <?php foreach ($timeSlots as $time => $status) : ?>
                                <?php
                                $statusText = $status;
                                $bookingName = $status == "ว่าง" ? '-' : ''; // Default value
                                $roomStatus = isset($_GET['roomStatus']) ? $_GET['roomStatus'] : 1; // ถ้าไม่พบค่า roomStatus ให้ถือว่าห้องพร้อมใช้งาน
                                $clickable = ($status == "ว่าง" && $roomStatus == 1) ? "clickable" : "not-available";

                                foreach ($result as $row) {
                                    $bookingStart = $row["booking_time_in"];
                                    $bookingEnd = $row["booking_time_out"];
                                    if ($time >= date("H:i", strtotime($bookingStart)) && $time < date("H:i", strtotime($bookingEnd))) {
                                        $bookingName = $row['member_name'] . ' ' . $row['member_lastname'];
                                        $statusText = $row['status'] == "approved" ? "กำลังใช้งาน" : ($row['status'] == "succeed" ? "ใช้งานเสร็จสิ้น" : "จองแล้ว");
                                        break;
                                    }
                                }
                                // ตรวจสอบสถานะห้องจาก roomStatus
                                if ($roomStatus == 0) {
                                    $statusText = "ห้องไม่พร้อมใช้งาน";
                                } elseif ($status == "ว่าง" && $statusText == "ว่าง") {
                                    $clickable = "clickable";
                                }
                                ?>
                                <tr class="<?= $clickable ?>" data-time="<?= $time ?>" data-room="<?= $room ?>" data-date="<?= $date ?>">
                                    <td><?= $time ?> - <?= date("H:i", strtotime("+1 hour", strtotime($time))) ?></td>
                                    <td><?= $bookingName ?></td>
                                    <td><?= $statusText ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>