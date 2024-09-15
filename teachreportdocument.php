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
    <title>Report Document</title>
</head>

<body>
    <h2>รายงานการใช้แบบฟอร์ม</h2>
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
</body>

</html>