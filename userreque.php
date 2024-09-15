<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
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
    <!-- <link rel="stylesheet" href="css/userreque.css"> -->
    <link rel="stylesheet" href="css/usreque.css">

    <title>User request</title>
</head>

<body>
    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-user.php");
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
            <form action="userreque_db.php" method="post">
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
                <h1>กรอกข้อมูลเพื่อขอรับบริการ</h1>
                <?php
                if (isset($_SESSION['user_login'])) {
                    $student_login = $_SESSION['user_login'];
                    $stmt = $conn->prepare("SELECT * FROM member WHERE member_id = :user_id");
                    $stmt->bindParam(':user_id', $student_login);
                    $stmt->execute();
                    $row = $stmt->fetch(PDO::FETCH_ASSOC);
                }
                // if ($row) {
                //     echo "ชื่อ - นามสกุล : " . htmlspecialchars(($row['member_name'] . " " . $row['member_lastname']));
                // }

                ?>

                <?php if ($row) { ?>
                    <p>ชื่อ - นามสกุล: <?php echo htmlspecialchars($row['member_name'] . " " . $row['member_lastname']); ?></p>
                <?php } ?>

                <div class="line">
                    <label for="service_request_history">เคยมีประวัติการรับบริการ :</label>
                    <select name="service_request_history" id="service_request_history">
                        <option value="ไม่เคยมีประวัติ">ไม่เคยมีประวัติ</option>
                        <option value="เคยมีประวัติ">เคยมีประวัติ</option>
                    </select>
                </div>

                <?php

                require_once 'config/conndb.php';

                $select_service_type = $conn->prepare('SELECT * FROM service_request_type');
                $select_service_type->execute();

                ?>
                <div class="line">
                    <label for="servicerequest_type_id">เรื่องที่ต้องการขอรับบริการ :</label>
                    <select name="servicerequest_type_id" id="servicerequest_type_id">
                        <option selected disabled value="">กรุณาเลือก</option>
                        <?php while ($row_type = $select_service_type->FETCH(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row_type['servicerequest_type_id'] ?>"><?php echo $row_type['servicerequest_type_name'] ?></option>
                        <?php } ?>
                    </select>
                </div>

                <div class="line-1">
                    <label for="service_request_Basicdetails">รายละเอียดเบื้องต้น :</label>
                    <textarea type="text" class="form_control" name="service_request_Basicdetails" aria-describedby="service_request_Basicdetails"></textarea>
                </div>
                <div class="line">
                    <label for="service_request_date">วันที่ :</label>
                    <input type="date" name="service_request_date">
                </div>
                <!-- <br></br> -->
                <?php

                require_once 'config/conndb.php';

                $select_shift_schedule = $conn->prepare('SELECT * FROM shift_schedule');
                $select_shift_schedule->execute();

                ?>
                <div class="line">
                    <label for="service_request_time">ช่วงเวลาเข้ารับบริการ :</label>
                    <select name="shift_schedule_id" id="shift_schedule_id" onchange="updateTimeOptions()">
                        <option selected disabled value="">กรุณาเลือก</option>
                        <option value="morning">เช้า</option>
                        <option value="afternoon">บ่าย</option>
                    </select>
                    <!-- <br></br> -->
                    <label for="time">เลือกเวลา :</label>
                    <select id="time" name="time">
                        <!-- Time options will be populated based on shift selection -->
                    </select>
                </div>
                <!-- <br></br> -->
                <div class="line">
                    <label for="service_request_gender">เพศของผู้ให้การปรึกษา :</label>
                    <select name="service_request_gender" id="service_request_gender">
                        <option value="ชาย">ชาย</option>
                        <option value="หญิง">หญิง</option>
                        <option value="ไม่ระบุ">ไม่ระบุ</option>
                    </select>
                </div>
                <!-- <br></br> -->
                <div class="line">
                    <label for="service_request_location">สถานที่ให้การปรึกษา :</label>
                    <select name="service_request_location" id="service_request_location">
                        <option value="online">ออนไลน์</option>
                        <option value="onsite">ออนไซต์</option>
                    </select>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">ส่ง</button>
            </form>
        </div>
    </main>
    <script>
        function updateTimeOptions() {
            const shift = document.getElementById("shift_schedule_id").value;
            const timeSelect = document.getElementById("time");

            // Clear previous options
            timeSelect.innerHTML = '';

            // Function to generate time options
            function generateTimes(start, end) {
                const times = [];
                let current = start;

                while (current <= end) {
                    times.push(current);
                    // Add 30 minutes
                    let [hours, minutes] = current.split(':').map(Number);
                    minutes += 30;
                    if (minutes === 60) {
                        hours += 1;
                        minutes = 0;
                    }
                    // Format time to HH:MM
                    current = `${String(hours).padStart(2, '0')}:${String(minutes).padStart(2, '0')}`;
                }

                return times;
            }

            if (shift === "morning") {
                const morningTimes = generateTimes("09:00", "12:00");
                morningTimes.forEach(time => {
                    const option = document.createElement("option");
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                });
            } else if (shift === "afternoon") {
                const afternoonTimes = generateTimes("13:00", "16:00");
                afternoonTimes.forEach(time => {
                    const option = document.createElement("option");
                    option.value = time;
                    option.textContent = time;
                    timeSelect.appendChild(option);
                });
            }
        }
    </script>
    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>