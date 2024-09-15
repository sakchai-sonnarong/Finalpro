<?php
session_start();
require_once 'config/conndb.php';

if(!isset($_SESSION['student_login'])){
    header('location: index.php');
}

if(isset($_POST['submit'])){
    $service_request_id = $_POST['service_request_id'];
    $dataconsult = $_POST['dataconsult'];
    $valueconsult = $_POST['valueconsult'];

    // ตรวจสอบค่าที่ต้องการ
    if(empty($service_request_id)){
        $_SESSION['error'] = 'กรุณาเลือกรายชื่อผู้รับบริการ';
    } else if(empty($dataconsult)){
        $_SESSION['error'] = 'กรุณากรอกข้อมูลการให้การปรึกษา';
    } else if(empty($valueconsult)){
        $_SESSION['error'] = 'กรุณากรอกข้อมูลผลการให้การปรึกษา';
    } else {
        // อัปเดตข้อมูล
        $update_sql = "UPDATE service_request
            SET service_value_data = :valueconsult, service_request_data = :dataconsult
            WHERE service_request_id = :service_request_id";
        $stmt = $conn->prepare($update_sql);
        $stmt->bindParam(':valueconsult', $valueconsult);
        $stmt->bindParam(':dataconsult', $dataconsult);
        $stmt->bindParam(':service_request_id', $service_request_id);

        // ตรวจสอบการ execute
        if ($stmt->execute()) {
            $_SESSION['success'] = 'บันทึกข้อมูลเรียบร้อยแล้ว';
            header("location: saveinfostd.php");
        } else {
            $_SESSION['error'] = 'เกิดข้อผิดพลาดในการอัพเดตข้อมูล';
            echo "Error: " . implode(":", $stmt->errorInfo());
            header("location: saveinfostd.php");
        }
    }

    header("location: saveinfostd.php");
    exit();
}
?>
