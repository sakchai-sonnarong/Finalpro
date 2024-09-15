<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
    exit();
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
    
    <link rel="stylesheet" href="css/redoct-std.css">
    <title>Report Document</title>

</head>

<body>
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
            <div class="title">
                <a href="adform.php">
                    <h2>จัดการแบบฟอร์ม</h2>
                </a>
                <h3>|</h3>
                <a href="reportdocument.php">
                    <h2>รายงานการแบบใช้ฟอร์ม</h2>
                </a>
            </div>
            <div class="title-1">
                <h4>แสดงข้อมูล</h4>
                <a href="reportdocument.php">
                    <h4>รายงานทั้งหมด</h4>
                </a>
                <h4>|</h4>
                <a href="reportdocumentteach.php">
                    <h4>รายงานของอาจารย์</h4>
                </a>
                <h4>|</h4>
                <a href="reportdocumentstd.php">
                    <h4>รายงานของนิสิต</h4>
                </a>
            </div>
            <div class="name-formstd">
                <h4>รายงานการใช้แบบฟอร์มของนิสิต</h4>
            </div>
            
            <table>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อแบบฟอร์ม</th>
                    <th>จำนวนการใช้งาน</th>
                </tr>

                <?php

                $sql = "SELECT DF.document_form_id, DF.document_form_data, 
                COUNT(BD.document_form_number) AS usage_count
                FROM
                member M,
                member_type MT,
                booking_room BR,
                booking_and_document BD,
                document_form DF

                WHERE
                M.member_id=BR.member_id AND
                BR.booking_id=BD.booking_id AND
                DF.document_form_id = BD.document_form_id AND
                MT.member_type_id=M.member_type_id AND
                MT.member_type_id=1 GROUP BY DF.document_form_id";

                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $forms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $count = 1;
                foreach ($forms as $form) {
                    echo "<tr>";
                    echo "<td>" . $count++ . "</td>";
                    echo "<td>" . htmlspecialchars($form['document_form_data']) . "</td>";
                    echo "<td>" . $form['usage_count'] . "</td>";
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