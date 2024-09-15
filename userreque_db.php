<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
}

if (isset($_POST['submit'])) {
    $id = $_SESSION['member_id']; // id ที่ได้มาจาก session
    $service_request_history = $_POST['service_request_history'];
    $servicerequest_type_id = $_POST['servicerequest_type_id'];
    $service_request_Basicdetails = $_POST['service_request_Basicdetails'];
    $service_request_date = $_POST['service_request_date'];
    $shift_schedule_id = $_POST['shift_schedule_id'];
    $service_request_time = $_POST['time'];
    $service_request_gender = $_POST['service_request_gender'];
    $service_request_location = $_POST['service_request_location'];

    if (empty($service_request_Basicdetails)) {
        $_SESSION['error'] = 'กรุณากรอก รายละเอียด';
        header("location: userreque.php");
    } else if (empty($servicerequest_type_id)) {
        $_SESSION['error'] = 'กรุณาระบุเรื่องที่ต้องการขอรับบริการ';
        header("location: userreque.php");
    }else if (empty($service_request_date)) {
        $_SESSION['error'] = 'กรุณาระบุวันที่เข้ารับบริการ';
        header("location: userreque.php");
    } else if (empty($shift_schedule_id)) {
        $_SESSION['error'] = 'กรุณาระบุช่วงเวลาเข้ารับบริการ';
        header("location: userreque.php");
    } else if (empty($service_request_gender)) {
        $_SESSION['error'] = 'กรุณาระบุเพศของผู้ให้บริการบริการ';
        header("location: userreque.php");
    } else if (empty($service_request_location)) {
        $_SESSION['error'] = 'กรุณาระบุสถานที่รับบริการ';
        header("location: userreque.php");
    } else {
        try {

            $stmt = $conn->prepare("SELECT shift_id FROM shift_schedule WHERE shift_name LIKE :shift_name AND shift_date = :shift_date");
            $shift_name_like = "%" . $shift_schedule_id . "%"; 
            $stmt->execute([':shift_name' => $shift_name_like, ':shift_date' => $service_request_date]);
            $shift_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            // เช็คว่าพบ shift_id หรือไม่
            if (empty($shift_ids)) {
                $_SESSION['error'] = "ไม่พบช่วงเวลาเข้ารับบริการที่เลือก";
                header("location: userreque.php");
                exit();
            }

            // เลือก shift_id ที่เหมาะสม
            $selected_shift_id = $shift_ids[0];

            $sql = $conn->prepare("INSERT INTO service_request (`member_id_request`, `service_request_history`, `servicerequest_type_id`, `service_request_Basicdetails`, `service_request_date`,`service_request_time`, `shift_id`, `service_request_gender`, `service_request_location`, `service_request_status`) 
                VALUES (:id, :service_request_history, :servicerequest_type_id, :service_request_Basicdetails, :service_request_date, :service_request_time, :shift_schedule_id, :service_request_gender, :service_request_location, 'รออนุมัติ')");
            $sql->bindParam(':id', $id);
            $sql->bindParam(':servicerequest_type_id', $servicerequest_type_id);
            $sql->bindParam(':service_request_Basicdetails', $service_request_Basicdetails);
            $sql->bindParam(':service_request_date', $service_request_date);
            $sql->bindParam(':shift_schedule_id', $selected_shift_id);
            $sql->bindParam(':service_request_time', $service_request_time);
            $sql->bindParam(':service_request_gender', $service_request_gender);
            $sql->bindParam(':service_request_location', $service_request_location);
            $sql->bindParam(':service_request_history', $service_request_history);

            if ($sql->execute()) {
                $_SESSION['success'] = "ส่งคำขอเรียบร้อย <a href='userreque.php' class='alert-link'>คลิ้กที่นี่</a> เพื่อส่งคำขอ";
                header("location: userreque.php");
            } else {
                $_SESSION['error'] = "มีข้อผิดพลาดเกิดขึ้นในการส่งคำขอ";
                header("location: userreque.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
