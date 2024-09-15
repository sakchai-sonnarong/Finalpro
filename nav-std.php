<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <link rel="stylesheet" href="css/nstd.css">

</head>

<body>

    <?php
    if (isset($_SESSION['student_login'])) {
        $admin_id = $_SESSION['student_login'];
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
        <li><a href="teststd.php"><i class='bi bi-house-door-fill'></i>
                <span class="link-name">หน้าหลัก</span>
            </a></li>
        <li><a href="bookingstd.php"><i class='bi bi-calendar2-week'></i>
                <span class="link-name">การจองห้องให้การปรึกษา</span>
            </a></li>
        <li><a href="infobookingstd.php"><i class='bi bi-calendar2-check-fill'></i>
                <span class="link-name">ข้อมูลการจองห้อง</span>
            </a></li>
        <li><a href="saveinfostd.php"><i class='bi bi-journal-plus'></i>
                <span class="link-name">บันทึกข้อมูลให้การปรึกษา</span>
            </a></li>
        <li><a href="viewinfostd.php"><i class='bi bi-journal-medical'>
                </i><span class="link-name">ดูบันทึกข้อมูลให้การปรึกษา</span>
            </a></li>
        <li><a href="requestd.php"><i class='bi bi-pencil-square'></i>
                <span class="link-name">การขอเข้ารับบริการ</span>
            </a></li>
        <li><a href="resultsstd.php"><i class='bi bi-briefcase-fill'></i>
                <span class="link-name">ผลคำขอเข้ารับบริการ</span>
            </a></li>
        <li><a href="assignedstd.php"><i class='bi bi-bag-plus-fill'></i>
                <span class="link-name">งานที่ได้รับมอบหมาย</span>
            </a></li>
        <li><a href="logout.php">
                <i class='bi bi-box-arrow-right'></i>
                <span class="link-name">ออกจากระบบ</span>
            </a></li>
    </ul>
</body>

</html>