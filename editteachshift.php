<?php
require_once 'config/conndb.php';

$teacher = [];
$day_name = '';
$shift_name = '';
$shift_period = '';
$message = '';
$teacherid = '';
$dayname = '';
$shiftperiod = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update']) && isset($_POST['day_name']) && isset($_POST['shift_period'])) {
        $teacherid = $_POST['teacher_id'];
        $dayname = $_POST['day_name'];
        $shiftperiod = $_POST['shift_period'];

        // Check if the teacher ID exists
        $check_sql = "SELECT COUNT(*) FROM member WHERE member_id = :teacher_id";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bindParam(':teacher_id', $teacherid);
        $check_stmt->execute();

        if ($check_stmt->fetchColumn() == 0) {
            $message = "Teacher ID does not exist.";
            header("Location: adschedule.php?day_name=" . urlencode($dayname) . "&shift_name=" . urlencode($shift_name) . "&shift_period=" . urlencode($shiftperiod) . "&message=" . urlencode($message));
            exit;
        }

        // Update the teacher assigned to the shift
        $update_sql = "UPDATE shift_schedule SET member_id_teacher = :teach_id WHERE shift_name LIKE :shiftname";
        $update_stmt = $conn->prepare($update_sql);
        $shiftname_pattern = '%' . $dayname . $shiftperiod . '%';

        $update_stmt->bindParam(':teach_id', $teacherid);
        $update_stmt->bindParam(':shiftname', $shiftname_pattern);

        $update_stmt->execute();

        // Set notification message
        $message = "แก้ไขข้อมูลเรียบร้อยแล้ว";

        // Redirect and refresh page
        header("Location: adschedule.php?day_name=" . urlencode($_POST['day_name']) . "&shift_name=" . urlencode($_POST['shift_name']) . "&shift_period=" . urlencode($_POST['shift_period']) . "&message=" . urlencode($message));
        exit;
    } else {

        // Fetch data for display
        $day_name = $_POST['day_name'];
        $shift_name = $_POST['shift_name'];
        $shift_period = $_POST['shift_period'];

        $sql = "SELECT m.member_name, m.member_lastname, m.member_id
        FROM shift_schedule ss
        JOIN member m ON ss.member_id_teacher = m.member_id
        WHERE DATE_FORMAT(shift_date, '%W') = :day_name AND shift_name LIKE :shift_name
        GROUP BY m.member_name";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':day_name' => $day_name,
            ':shift_name' => '%' . $shift_period . '%'
        ]);
        $teacher = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    // Get the message from the query parameters if redirected
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
    }

    // Pre-fill day_name, shift_name, and shift_period from the query parameters
}
$day_name_thai = '';
switch ($day_name) {
    case 'Monday':
        $day_name_thai = 'จันทร์';
        break;
    case 'Tuesday':
        $day_name_thai = 'อังคาร';
        break;
    case 'Wednesday':
        $day_name_thai = 'พุธ';
        break;
    case 'Thursday':
        $day_name_thai = 'พฤหัสบดี';
        break;
    case 'Friday':
        $day_name_thai = 'ศุกร์';
        break;
    case 'Saturday':
        $day_name_thai = 'เสาร์';
        break;
    case 'Sunday':
        $day_name_thai = 'อาทิตย์';
        break;
    default:
        $day_name_thai = $day_name; // In case it's already in Thai or not matched
}

// Convert the shift periods to Thai
$shift_period_thai = '';
switch ($shift_period) {
    case 'morning':
        $shift_period_thai = 'เช้า';
        break;
    case 'afternoon':
        $shift_period_thai = 'บ่าย';
        break;
    default:
        $shift_period_thai = $shift_period; // In case it's already in Thai or not matched
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

    <link rel="stylesheet" href="css/edit-tshif.css">
    <title>Edit Teacher to Shift</title>
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
            <h1>แก้ไขอาจารย์ประจำเวร</h1>
            <h2>รายชื่ออาจารย์ประจำเวร <?= htmlspecialchars($day_name_thai) ?> ช่วง <?= htmlspecialchars($shift_period_thai) ?></h2>
            <?php

            $selectteacher = $conn->prepare('SELECT * FROM member WHERE member_type_id = 2');
            $selectteacher->execute();

            ?>
            <?php if (count($teacher) > 0): ?>
                <form method="post" action="">
                    <input type="hidden" name="day_name" value="<?= htmlspecialchars($day_name) ?>">
                    <input type="hidden" name="shift_name" value="<?= htmlspecialchars($shift_name) ?>">
                    <input type="hidden" name="shift_period" value="<?= htmlspecialchars($shift_period) ?>">
                    <label for="teacher_select">เลือกอาจารย์:</label>
                    <select name="teacher_id" id="teacher_select">
                        <option value="">
                            <?= htmlspecialchars($shift_name) ?>
                        </option>
                        <?php while ($teacherRow = $selectteacher->FETCH(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?= htmlspecialchars($teacherRow['member_id']) ?>" <?php echo ($teacherRow['member_id'] == $teacherid) ? 'selected' : ''; ?>>
                                <?= htmlspecialchars($teacherRow['member_name']) . " " . htmlspecialchars($teacherRow['member_lastname']) ?>
                            </option>
                        <?php } ?>
                    </select>
                    <br><br>
                    <button type="submit" name="update">ยืนยันการเลือกอาจารย์</button>
                </form>
            <?php else: ?>
                <p>***ไม่มีข้อมูลอาจารย์ในช่วงเวลานี้***</p>
            <?php endif; ?>

</body>

</html>