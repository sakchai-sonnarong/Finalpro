<?php
session_start();
require_once 'config/conndb.php';

if(!isset($_SESSION['teacher_login'])){
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

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="css/tform_report.css"> -->
    <link rel="stylesheet" href="css/tformreport.css">

    <title>Teacher Form Report</title>
    <style>
        
.title-1 {
    display: flex;                   /* จัดเรียงภายใน div ให้อยู่ในแนวนอน */
    justify-content: center;
    align-items: center;             /* จัดให้ vertical-align center */
    margin-bottom: 20px;            
}

.title-1 a {
    text-decoration: none; /* ลบเส้นใต้ */
    font-family: "Prompt", sans-serif; /* ตั้งค่า font */
    font-size: 20px; /* ขนาดตัวอักษร */
    color: black;
}

.title-1 h4 {
    margin: 0 10px; /* ระยะห่างระหว่างลิงก์และตัวแบ่ง */
    font-family: "Prompt", sans-serif; /* ตั้งค่า font */
    font-size: 20px; /* ขนาดตัวอักษร */
}

.title-1 a:first-child h4 {
    color: #278fff; /* Set font color to blue for the first link */
}

.title-1 a:not(:first-child) h4:hover {
    color: #278fff; /* Change font color to blue on hover for other links */
}

.title-1 h4 {
    margin: 0 10px; /* Spacing between links and dividers */
    font-family: "Prompt", sans-serif; /* Set font family */
    font-size: 20px; /* Font size */
}

    </style>
</head>

</style>

<body>

    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-teacher.php");
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
            <h2>รายงานการใช้แบบฟอร์ม</h2>
            <div class="title-1">
                <h4>แสดงข้อมูล</h4>
                <a href="teachform_report.php">
                    <h4>รายงานทั้งหมด</h4>
                </a>
                <h4>|</h4>
                <a href="addreportdocumentteach.php">
                    <h4>รายงานของอาจารย์</h4>
                </a>
                <h4>|</h4>
                <a href="addreportdocumentstd.php">
                    <h4>รายงานของนิสิต</h4>
                </a>
            </div>
            <table>
                <tr>
                    <th>ลำดับ</th>
                    <th>ชื่อแบบฟอร์ม</th>
                    <th>จำนวนการใช้งาน</th>
                </tr>

                <?php

                $sql = "SELECT df.document_form_id, df.document_form_data, COUNT(bad.document_form_id) AS usage_count 
                FROM booking_and_document bad 
                JOIN document_form df ON df.document_form_id = bad.document_form_id
                GROUP BY df.document_form_id, df.document_form_data";
                $stmt = $conn->prepare($sql);
                $stmt->execute();
                $froms = $stmt->fetchAll(PDO::FETCH_ASSOC);

                $count = 1;
                foreach ($froms as $form) {
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

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>