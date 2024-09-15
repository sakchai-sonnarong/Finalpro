<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

// $sql = $conn->query("SELECT * FROM room");

// $stmt = $sql->fetchAll(PDO::FETCH_ASSOC);

try {
    $sql = $conn->query("SELECT * FROM room");
    $stmt = $sql->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Addroom</title>

    <!-- <link rel="stylesheet" href="css/addrroomsty.css"> -->
    <link rel="stylesheet" href="css/addr.css">
</head>

<body>

    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-admin.php");
            ?>
        </nav>
    </header>
    <a href="javascript:void(0)" onclick="w3_open()" class="bi bi-list" id="MenuBtn"></a>

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
            <h2>จัดการห้องศูนย์ให้การปรึกษา</h2>
            <div class="button-container">
                <a href="insertroom.php"><button type="add" name="add">เพิ่ม</button></a>
            </div>
            
            <table>
                <tr>
                    <th>ลำดับห้อง</th>
                    <th>ชื่อห้อง</th>
                    <th>สถานะห้อง</th>
                </tr>
                <?php
                if ($stmt && count($stmt) > 0) {
                    foreach ($stmt as $row) {
                        echo "<tr>";
                        echo "<td>" . htmlspecialchars($row["room_id"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["room_name"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["room_status"]) . "</td>";
                        echo "<td>";
                        echo "<form method='POST' action='editroom.php' style='display:inline-block;'>";
                        echo "<input type='hidden' name='room_id' value='" . htmlspecialchars($row["room_id"]) . "'>";
                        echo "<button id='succeedButton_" . htmlspecialchars($row["room_id"]) . "' type='submit' name='edit'>แก้ไข</button>";
                        echo "</form>";
                        echo "<form method='POST' action='crudroom_db.php' style='display:inline-block;'>";
                        echo "<input type='hidden' name='room_id' value='" . htmlspecialchars($row["room_id"]) . "'>";
                        echo "<button type='submit' name='action' value='delete' onclick='return confirm(\"คุณต้องการลบการจองนี้หรือไม่?\");'>ลบ</button>";
                        echo "</form>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>ไม่มีข้อมูลห้องในฐานข้อมูล</td></tr>";
                }
                ?>
            </table>
        </div>

    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>