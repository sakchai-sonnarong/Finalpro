<?php
require_once 'config/conndb.php';

$students = [];
$day_name = '';
$shift_period = '';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['delete'])) {
        // การลบข้อมูล
        $student_id = $_POST['student_id'];
        $delete_sql = "DELETE FROM shift_student_assignment WHERE member_student_id = :student_id";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->execute([':student_id' => $student_id]);

        // ตั้งข้อความแจ้งเตือน
        $message = "ลบเสร็จสิ้น";

        // รีเฟรชหน้า
        header("Location: stdviewshift.php?day_name=" . urlencode($_POST['day_name']) . "&shift_name=" . urlencode($_POST['shift_name']) . "&shift_period=" . urlencode($_POST['shift_period']) . "&message=" . urlencode($message));
        exit;
    } else {
        // การดึงข้อมูลเพื่อแสดง
        $day_name = $_POST['day_name'];
        $shift_name = $_POST['shift_name'];
        $shift_period = $_POST['shift_period'];

        $sql = "SELECT m.member_name, m.member_lastname, m.member_id
                FROM shift_schedule ss
                JOIN shift_student_assignment ssa ON ss.shift_id = ssa.shift_id
                JOIN member m ON ssa.member_student_id = m.member_id
                WHERE DATE_FORMAT(shift_date, '%W') = :day_name AND shift_name LIKE :shift_name
                GROUP BY m.member_name";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':day_name' => $day_name,
            ':shift_name' => '%' . $shift_period . '%'
        ]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} else {
    // Get the message from the query parameters if redirected
    if (isset($_GET['message'])) {
        $message = $_GET['message'];
    }

    // Pre-fill day_name, shift_name, and shift_period from the query parameters
    if (isset($_GET['day_name']) && isset($_GET['shift_name']) && isset($_GET['shift_period'])) {
        $day_name = $_GET['day_name'];
        $shift_name = $_GET['shift_name'];
        $shift_period = $_GET['shift_period'];

        $sql = "SELECT m.member_name, m.member_lastname, m.member_id
                FROM shift_schedule ss
                JOIN shift_student_assignment ssa ON ss.shift_id = ssa.shift_id
                JOIN member m ON ssa.member_student_id = m.member_id
                WHERE DATE_FORMAT(shift_date, '%W') = :day_name AND shift_name LIKE :shift_name
                GROUP BY m.member_name";

        $stmt = $conn->prepare($sql);
        $stmt->execute([
            ':day_name' => $day_name,
            ':shift_name' => '%' . $shift_period . '%'
        ]);
        $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
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

    <link rel="stylesheet" href="css/adstd-vshif.css">

    <title>View Student Shift</title>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            var message = "<?php echo htmlspecialchars($message); ?>";
            if (message) {
                alert(message);
            }
        });
    </script>
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

    <main>
        <div id="container">
            <div class="head">
                <a href="stdshifts.php" class="bi bi-arrow-left" id="BackBtn"></a>
                <h1>รายชื่อนิสิตประจำเวร <?= htmlspecialchars($day_name_thai) ?> ช่วง <?= htmlspecialchars($shift_period_thai) ?></h1>
            </div>
            <a href="selectstudenttoshifts.php?day_name=<?= urlencode($day_name) ?>&shift_period=<?= urlencode($shift_period) ?>">
                <button class="button-addstd">เพิ่มนิสิตเข้าเวร</button></a>
            <?php if (count($students) > 0): ?>
                <table border="1">
                    <thead>
                        <tr>
                            <th>ชื่อ - นามสกุล</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['member_name']) . " " . htmlspecialchars($student['member_lastname']); ?></td>
                                <td>
                                    <form method="post" style="display:inline;">
                                        <input type="hidden" name="student_id" value="<?= $student['member_id'] ?>">
                                        <input type="hidden" name="day_name" value="<?= htmlspecialchars($day_name) ?>">
                                        <input type="hidden" name="shift_name" value="<?= htmlspecialchars($shift_name) ?>">
                                        <input type="hidden" name="shift_period" value="<?= htmlspecialchars($shift_period) ?>">
                                        <button type="submit" name="delete">ลบ</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>***ไม่มีข้อมูลนิสิตในช่วงเวลานี้***</p>
            <?php endif; ?>
        </div>
    </main>
</body>

</html>