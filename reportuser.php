<?php

session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

$name = isset($_POST['name']) ? $_POST['name'] : '';
$lastname = isset($_POST['lastname']) ? $_POST['lastname'] : '';
$user_type = isset($_POST['user_type']) ? $_POST['user_type'] : '';
$request_type_name = isset($_POST['selectrequest_type']) ? $_POST['selectrequest_type'] : '';

$sql = "SELECT m.*, mt.member_type_name,sr.*,st.*
        FROM service_request sr
        JOIN member m ON sr.member_id_request = m.member_id
        JOIN member_type mt ON m.member_type_id = mt.member_type_id 
        JOIN service_request_type st ON sr.servicerequest_type_id = st.servicerequest_type_id
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

if (!empty($request_type_name)) {
    $sql .= " AND st.servicerequest_type_name = :request_type_name";
    $params[':request_type_name'] = $request_type_name;
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

    <link rel="stylesheet" href="css/ad-reportuser.css">
    <title>Document</title>
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
            <h2>รายงานการขอรับบริการ</h2>
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
                        <label for="user_type">ประเภทผู้ใช้งาน :</label>
                        <select name="user_type" id="user_type">
                            <option value="">-- เลือกประเภทผู้ใช้ --</option>
                            <option value="1" <?= $user_type == 'student' ? 'selected' : '' ?>>นิสิตสาขาจิตวิทยา</option>
                            <option value="3" <?= $user_type == 'user' ? 'selected' : '' ?>>ผู้ขอรับบริการ</option>
                        </select>
                    </div>
                    <?php

                    $selectrequest_type = $conn->prepare('SELECT * FROM service_request_type');
                    $selectrequest_type->execute();

                    ?>
                    <div class="title-form">
                        <label for="request_typeselect">เรื่องที่ขอรับบริการ :
                            <select name="selectrequest_type" id="request_type_id">
                                <option selected disabled value="">เลือกเรื่องที่ขอรับบริการ</option>
                                <?php while ($request_types = $selectrequest_type->FETCH(PDO::FETCH_ASSOC)) { ?>
                                    <option value="<?php echo $request_types['servicerequest_type_name'] ?>" <?php echo ($request_types['servicerequest_type_name'] == $request_type_name) ? 'selected' : ''; ?>><?php echo $request_types['servicerequest_type_name'] ?></option>
                                <?php } ?>
                            </select>
                        </label>
                    </div>
                    <button type="submit" class="btnSeach">ค้นหา</button>
                </form>
            </div>

            <div class="b-table">
                <table border="1">
                    <tr>
                        <th>รหัสคำขอ</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>วันที่ขอรับบริการ</th>
                        <th>เวลา</th>
                        <th>เรื่องที่ขอรับบริการ</th>
                        <th>ประเภทสมาชิก</th>
                    </tr>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['service_request_id']) ?></td>
                                <td><?= htmlspecialchars($row['member_name']) . " " . htmlspecialchars($row['member_lastname']) ?></td>
                                <td><?= htmlspecialchars($row['service_request_date']) ?></td>
                                <td><?= htmlspecialchars($row['service_request_time']) ?></td>
                                <td><?= htmlspecialchars($row['servicerequest_type_name']) ?></td>
                                <td><?= htmlspecialchars($row['member_type_name']) ?></td>
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