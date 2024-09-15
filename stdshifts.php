<?php
session_start();
require_once 'config/conndb.php';

$selectteacher = $conn->prepare('SELECT * FROM member WHERE member_type_id = 1');
$selectteacher->execute();

// Query to get shift data
$sql = "SELECT DATE_FORMAT(shift_date, '%W') AS day_name,
MAX(CASE WHEN shift_name LIKE '%morning%' THEN CONCAT(m.member_name,' ',member_lastname) END) AS morning_shift,
MAX(CASE WHEN shift_name LIKE '%afternoon%' THEN CONCAT(m.member_name,' ',member_lastname) END) AS afternoon_shift
FROM shift_schedule ss
JOIN shift_student_assignment ssa ON ss.shift_id = ssa.shift_id
LEFT JOIN member m ON ssa.member_student_id = m.member_id
WHERE DAYOFWEEK(shift_date) BETWEEN 2 AND 6
GROUP BY shift_name
ORDER BY shift_date;";


$stmt = $conn->prepare($sql);
$stmt->execute();
$shifts = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <title>Assign Teacher to Shifts</title>
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/adstd-shifts.css">

</head>

<body>

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
            <h1>จัดการตารางเวรนิสิต</h1>
            <div class="title">
                <a href="adschedule.php">
                    <h2>ตารางเวรอาจารย์</h2>
                </a>
                <h3>|</h3>
                <a href="stdshifts.php">
                    <h2>ตารางเวรนิสิต</h2>
                </a>
            </div>

            <div class="table-schedule">
                <table>
                    <tr>
                        <th>วัน/เวลา</th>
                        <th>ช่วงเช้า<br>เวลา 09:00 - 12:00 น.</th>
                        <th>ช่วงบ่าย<br>เวลา 13:00 - 17:00 น.</th>
                    </tr>
                    <?php
                    // Define days of the week
                    $days_of_week = [
                        'Monday' => 'จันทร์',
                        'Tuesday' => 'อังคาร',
                        'Wednesday' => 'พุธ',
                        'Thursday' => 'พฤหัสบดี',
                        'Friday' => 'ศุกร์'
                    ];

                    // Display the data in the table
                    foreach ($days_of_week as $day_key => $day_name) {
                        // Check if there's data for the current day
                        $shift = array_filter($shifts, function ($s) use ($day_key) {
                            return $s['day_name'] === $day_key;
                        });
                        $shift = reset($shift); // Get the first (and only) matching result

                        echo "<tr>";
                        echo "<td>" . $day_name . "</td>";
                        echo "<td><form action='stdviewshift.php' method='POST'>
                        <input type='hidden' name='day_name' value='" . $day_key . "'>
                        <input type='hidden' name='shift_name' value='" . ($shift['morning_shift'] ?? '') . "'>
                        <input type='hidden' name='shift_period' value='morning'>
                        <button type='submit'>รายชื่อเวรเข้า</button>
                    </form></td>";
                        echo "<td><form action='stdviewshift.php' method='POST'>
                        <input type='hidden' name='day_name' value='" . $day_key . "'>
                        <input type='hidden' name='shift_name' value='" . ($shift['afternoon_shift'] ?? '') . "'>
                        <input type='hidden' name='shift_period' value='afternoon'>
                        <button type='submit'>รายชื่อเวรบ่าย</button>
                    </form></td>";
                        echo "</tr>";
                    }
                    ?>

                </table>
            </div>
        </div>
    </main>
    <header>
        <?php
        if (isset($_SESSION['admin_login'])) {
            $admin_id = $_SESSION['admin_login'];
            $stmt = $conn->prepare("SELECT * FROM member WHERE member_id = :admin_id");
            $stmt->bindParam(':admin_id', $admin_id);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row) {
                // Display the user's name and lastname
                echo '<ul><li><a href="profile.php" class="user-link"><span class="user">ผู้ใช้งาน : ' . '<br>' . htmlspecialchars($row['member_name'] . ' ' . $row['member_lastname']) . '</span></a></li></ul>';
            }
        }
        ?>
        <!-- === NAV BAR === -->
        <nav>
            <ul>
                <li><a href="admin.php"><i class='bi bi-house-door-fill'></i>
                        <span class="link-name">หน้าหลัก</span>
                    </a></li>
                <li><a href="aduser.php"><i class='bi bi-people'></i>
                        <span class="link-name">ข้อมูลผู้ใช้งาน</span>
                    </a></li>
                <li><a href="adschedule.php"><i class='bi bi-calendar4-week'></i>
                        <span class="link-name">จัดการตารางเวร</span>
                    </a></li>
                <li><a href="adroom.php"><i class='bi bi-file-earmark-diff'></i>
                        <span class="link-name">จัดการห้องศูนย์ให้การปรึกษา</span>
                    </a></li>
                <li><a href="adform.php"><i class='bi bi-files'>
                        </i><span class="link-name">จัดการแบบฟอร์ม</span>
                    </a></li>
                <li><a href="reportbook.php">
                        <i class='bi bi-clipboard-check'></i>
                        <span class="link-name">รายงานการจองห้อง</span>
                    </a></li>
                <li><a href="reportuser.php">
                        <i class='bi bi-file-earmark-text'></i>
                        <span class="link-name">รายงานการขอรับบริการ</span>
                    </a></li>
                <li><a href="logout.php">
                        <i class='bi bi-box-arrow-right'></i>
                        <span class="link-name">ออกจากระบบ</span>
                    </a></li>
            </ul>
        </nav>
    </header>
    <a href="javascript:void(0)" onclick="w3_open()" class="bi bi-list" id="MenuBtn"></a>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>