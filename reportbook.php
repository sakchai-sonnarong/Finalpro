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
$room_name = isset($_POST['selectroom']) ? $_POST['selectroom'] : '';

$sql = "SELECT m.*, mt.member_type_name, r.room_name,br.*
        FROM booking_room br
        JOIN member m ON br.member_id = m.member_id
        JOIN member_type mt ON m.member_type_id = mt.member_type_id 
        JOIN room r ON br.room_id = r.room_id
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

if (!empty($room_name)) {
    $sql .= " AND r.room_name = :room_name";
    $params[':room_name'] = $room_name;
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

    <link rel="stylesheet" href="css/ad-reportbook.css">
    <title>report booking</title>
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
            <h2>รายงานการจองห้อง</h2>
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

                    <?php

                    $selectroom = $conn->prepare('SELECT * FROM room');
                    $selectroom->execute();

                    ?>
                    <div class="title-form">
                        <label for="roomselect">ห้องศูนย์ให้การปรึกษา :
                        <select name="selectroom" id="room_id">
                            <option selected disabled value="">ห้องที่ต้องการค้นหา</option>
                            <?php while ($rooms = $selectroom->FETCH(PDO::FETCH_ASSOC)) { ?>
                                <option value="<?php echo $rooms['room_name'] ?>" <?php echo ($rooms['room_name'] == $room_name) ? 'selected' : ''; ?>><?php echo $rooms['room_name'] ?></option>
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
                        <th>รหัสจอง</th>
                        <th>ชื่อ - นามสกุล</th>
                        <th>วันที่จอง</th>
                        <th>เวลา</th>
                        <th>ห้อง</th>
                        <th>ประเภทสมาชิก</th>
                    </tr>
                    <?php if (!empty($rows)): ?>
                        <?php foreach ($rows as $row): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['booking_id']) ?></td>
                                <td><?= htmlspecialchars($row['member_name']) . " " . htmlspecialchars($row['member_lastname']) ?></td>
                                <td><?= htmlspecialchars($row['booking_date']) ?></td>
                                <td><?= htmlspecialchars($row['booking_time_in']) . "-" . htmlspecialchars($row['booking_time_out']) ?></td>
                                <td><?= htmlspecialchars($row['room_name']) ?></td>
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