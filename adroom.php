<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

$sql = $conn->query("SELECT * FROM room");

$stmt = $sql->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- <link rel="stylesheet" href="css/addm_room.css"> -->
    <link rel="stylesheet" href="css/addroom_admin.css">

    <title>Adroom</title>
</head>

<body>

    <!-- ==== All Body Content ==== -->
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
            <h1>จัดการห้องศูนย์ให้การปรึกษา</h1>
            <div class="button-container">
                <a href="insertroom.php"><button>เพิ่ม</button></a>
            </div>

            <table>
                <tr>
                    <th>ลำดับห้อง</th>
                    <th>ชื่อห้อง</th>
                    <th>สถานะห้อง</th>
                    <th></th>
                </tr>
                <?php
                // แสดงข้อมูลในตาราง HTML
                foreach ($stmt as $row) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["room_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["room_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["room_status"]) . "</td>";
                    echo "<td>";
                    echo "<form method='POST' action='editroom.php' style='display:inline-block;'>";
                    echo "<input type='hidden' name='room_id' value='" . htmlspecialchars($row["room_id"]) . "'>";
                    echo "<button id='succeedButton_" . htmlspecialchars($row["room_id"]) . "' type='submit' name='edit' class='button-edit' >แก้ไข</button>"; //onclick='hideButton(\"succeedButton_" . htmlspecialchars($row["room_id"]) . "\")'
                    echo "</form>";
                    echo "<form method='POST' action='crudroom_db.php' style='display:inline-block;'>";
                    echo "<input type='hidden' name='room_id' value='" . htmlspecialchars($row["room_id"]) . "'>";
                    echo "<button type='submit' name='action' value='delete' class='button-delete' onclick='return confirm(\"คุณต้องการลบการจองนี้หรือไม่?\");'>ลบ</button>";
                    echo "</form>";
                    echo "</td>";
                    echo "</tr>";
                }
                ?>
            </table>
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