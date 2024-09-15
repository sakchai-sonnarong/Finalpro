<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <link rel="stylesheet" href="css/nuser.css">

</head>

<body>

    <?php
    if (isset($_SESSION['user_login'])) {
        $admin_id = $_SESSION['user_login'];
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
        <li><a href="user.php"><i class='bi bi-house-door-fill'></i>
                <span class="link-name">หน้าหลัก</span>
            </a></li>
        <li><a href="userreque.php"><i class='bi bi-pencil-square'></i>
                <span class="link-name">การขอเข้ารับบริการ</span>
            </a></li>
        <li><a href="userresults.php"><i class='bi bi-briefcase-fill'></i>
                <span class="link-name">ผลคำขอเข้ารับบริการ</span>
            </a></li>
        <li><a href="logout.php">
                <i class='bi bi-box-arrow-right'></i>
                <span class="link-name">ออกจากระบบ</span>
            </a></li>
    </ul>
</body>

</html>