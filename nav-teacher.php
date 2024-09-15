<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="css/nteach.css">

</head>

<body>

    <?php
    if (isset($_SESSION['teacher_login'])) {
        $admin_id = $_SESSION['teacher_login'];
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

    <ul>
        <li><a href="teachindex.php"><i class='bi bi-house-door-fill'></i>
                <span class="link-name">หน้าหลัก</span>
            </a></li>
        <li><a href="teachshowbooking.php"><i class='bi bi-calendar2-week'></i>
                <span class="link-name">การจองห้องให้การปรึกษา</span>
            </a></li>
        <li><a href="teachinfobooking.php"><i class='bi bi-calendar2-check-fill'></i>
                <span class="link-name">ข้อมูลการจองห้อง</span>
            </a></li>
        <li><a href="teachsave.php"><i class='bi bi-journal-plus'></i>
                <span class="link-name">บันทึกข้อมูลให้การปรึกษา</span>
            </a></li>
        <li><a href="teachshowsave.php"><i class='bi bi-journal-medical'>
                </i><span class="link-name">ดูบันทึกข้อมูลให้การปรึกษา</span>
            </a>
        <li>
        <li><a href="teachreque.php"><i class='bi bi-pencil-square'></i>
                <span class="link-name">คำขอเข้ารับบริการ</span>
            </a></li>
        <li><a href="teachassign.php"><i class='bi bi-briefcase-fill'></i>
                <span class="link-name">มอบหมายงาน</span>
            </a></li>
        <li><a href="teachform_report.php"><i class='bi bi-bag-plus-fill'></i>
                <span class="link-name">รายงานการใช้แบบฟอร์มเอกสาร</span>
            </a></li>
        <li><a href="logout.php">
                <i class='bi bi-box-arrow-right'></i>
                <span class="link-name">ออกจากระบบ</span>
            </a></li>
    </ul>

</body>

</html>