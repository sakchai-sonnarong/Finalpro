<?php
// session_start();
require_once 'config/conndb.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- <link rel="stylesheet" href="css/navadmin.css"> -->
    <link rel="stylesheet" href="css/nadmin.css">

</head>

<body>

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

</body>

</html>