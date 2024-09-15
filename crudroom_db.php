<?php
session_start();
require_once 'config/conndb.php';

if(!isset($_SESSION['admin_login'])){
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // รับข้อมูลจากฟอร์มเพิ่มห้อง
        $room_name = $_POST['room_name'];
        $room_status = $_POST['room_status'];

        try {
            // SQL สำหรับเพิ่มข้อมูลลงในตาราง room
            $sql = "INSERT INTO room (room_name, room_status) VALUES (:room_name, :room_status)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':room_name', $room_name);
            $stmt->bindParam(':room_status', $room_status);
            $stmt->execute();

            echo "เพิ่มข้อมูลห้องเรียบร้อยแล้ว!";
            header("Location: adroom.php");
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['edit'])) {
        // รับข้อมูลจากฟอร์มแก้ไขห้อง
        $room_id = $_POST['room_id'];
        $room_name = $_POST['room_name'];
        $room_status = $_POST['room_status'];

        try {
            // อัปเดตข้อมูลห้องในฐานข้อมูล
            $sql = "UPDATE room SET room_name = :room_name, room_status = :room_status WHERE room_id = :room_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':room_name', $room_name);
            $stmt->bindParam(':room_status', $room_status);
            $stmt->bindParam(':room_id', $room_id);
            $stmt->execute();

            echo "อัปเดตข้อมูลห้องเรียบร้อยแล้ว!";
            header("Location: adroom.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // รับข้อมูลสำหรับการลบ
        $room_id = $_POST['room_id'];

        try {
            // ลบการจองออกจากฐานข้อมูล
            $delete_related_sql = "DELETE FROM booking_room WHERE room_id = :room_id";
            $delete_related_stmt = $conn->prepare($delete_related_sql);
            $delete_related_stmt->bindParam(':room_id', $room_id);
            $delete_related_stmt->execute();

            // ลบการจองออกจากฐานข้อมูล
            $delete_sql = "DELETE FROM room WHERE room_id = :room_id LIMIT 1";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bindParam(':room_id', $room_id);
            $delete_stmt->execute();
            echo "ลบข้อมูลห้องเรียบร้อยแล้ว!";
            header("Location: adroom.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // ปิดการเชื่อมต่อ
    $conn = null;
}
?>