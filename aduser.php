<?php
session_start();
require_once 'config/conndb.php';

$name = isset($_POST['name']) ? $_POST['name'] : '';
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
$user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';

$sql = "SELECT m.*, mt.member_type_name
        FROM member m JOIN member_type mt ON mt.member_type_id = m.member_type_id 
        WHERE 1";
$params = [];

if (!empty($name)) {
    $sql .= " AND m.member_name LIKE :name";
    $params[':name'] = '%' . $name . '%';
}

if (!empty($lastname)) {
    $sql .= " AND m.member_lastname LIKE :lastname";
    $params[':lastname'] = '%' . $lastname . '%';
}

if (!empty($user_type)) {
    $sql .= " AND m.member_type_id = :user_type";
    $params[':user_type'] = $user_type;
}

$stmt = $conn->prepare($sql);
$stmt->execute($params);
$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- <link rel="stylesheet" href="css/ad-aduser.css"> -->
    <link rel="stylesheet" href="css/add-aduser.css">
    <title>ข้อมูลผู้ใช้งาน</title>

</head>

<body>

    <main>
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
            <h1>ข้อมูลผู้ใช้งาน</h1>
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
            <?php if (isset($_SESSION['warning'])) { ?>
                <div class="alert alert-warning" role="alert">
                    <?php
                    echo $_SESSION['warning'];
                    unset($_SESSION['warning']);
                    ?>
                </div>
            <?php } ?>
            <div id="head-form">
                <form method="post" action="" class="form-1">
                    <div class="title-form">
                        <label for="name">ชื่อ :</label>
                        <input type="text" name="name" id="name" value="<?= htmlspecialchars($name) ?>">
                    </div>
                    <div class="title-form">
                        <label for="lastname">นามสกุล :</label>
                        <input type="text" name="lastname" id="lastname" value="<?= htmlspecialchars($lastname) ?>">
                    </div>
                    <div class="title-form">
                        <label for="user_type">ประเภทสมาชิก :</label>
                        <select name="user_type" id="user_type">
                            <option value="">-- เลือกประเภทสมาชิก --</option>
                            <option value="1" <?= $user_type == 'student' ? 'selected' : '' ?>>นิสิตสาขาจิตวิทยา</option>
                            <option value="2" <?= $user_type == 'teacher' ? 'selected' : '' ?>>อาจารย์</option>
                            <option value="3" <?= $user_type == 'user' ? 'selected' : '' ?>>ผู้ขอรับบริการ</option>
                        </select>
                    </div>
                    <button type="submit" class="btnSeach">ค้นหา</button>
                </form>
            </div>

            <div class="b-table">
                <a href="adduser.php"><button class="btnAdd">เพิ่ม</button></a>

                <table border="1">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ชื่อ</th>
                            <th>นามสกุล</th>
                            <th>เพศ</th>
                            <th>ประเภทสมาชิก</th>
                            <th>ข้อมูลเพิ่มเติม</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($rows)): ?>
                            <?php foreach ($rows as $index => $row): ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td><?= htmlspecialchars($row['member_name']) ?></td>
                                    <td><?= htmlspecialchars($row['member_lastname']) ?></td>
                                    <td><?= htmlspecialchars($row['member_gender']) ?></td>
                                    <td><?= htmlspecialchars($row['member_type_name']) ?></td>
                                    <td>
                                        <form action="detailuser.php" method="post">
                                            <input type="hidden" name="member_id" value="<?= htmlspecialchars($row['member_id']); ?>">
                                            <button type="submit" name="view" class="detail">ข้อมูลเพิ่มเติม</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6">ไม่พบข้อมูล</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
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