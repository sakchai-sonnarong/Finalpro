<?php 
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['student_login'])) {
    // ถ้ายังไม่ได้ล็อกอิน ให้ redirect ไปหน้า login
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

// ตรวจสอบการส่งค่าผ่าน POST
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['booking_id'])) {
    $booking_id = $_POST['booking_id'];

    if (isset($_POST['status'])) {
        $new_status = $_POST['status'];

        // อัปเดตสถานะการจองในฐานข้อมูล
        $update_sql = "UPDATE booking_room SET status = :status WHERE booking_id = :booking_id";
        $update_stmt = $conn->prepare($update_sql);
        $update_stmt->bindParam(':status', $new_status);
        $update_stmt->bindParam(':booking_id', $booking_id);
        $update_stmt->execute();
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // ลบข้อมูลที่เกี่ยวข้องในตารางลูกก่อน
        $delete_related_sql = "DELETE FROM booking_and_document WHERE booking_id = :booking_id";
        $delete_related_stmt = $conn->prepare($delete_related_sql);
        $delete_related_stmt->bindParam(':booking_id', $booking_id);
        $delete_related_stmt->execute();

        // ลบการจองออกจากตารางหลัก
        $delete_sql = "DELETE FROM booking_room WHERE booking_id = :booking_id LIMIT 1";
        $delete_stmt = $conn->prepare($delete_sql);
        $delete_stmt->bindParam(':booking_id', $booking_id);
        $delete_stmt->execute();
    }

    // หลังจากอัปเดตหรือลบเสร็จ redirect กลับไปยังหน้าเดิม
    header("Location: infobookingstd.php");
    exit();
}
?>