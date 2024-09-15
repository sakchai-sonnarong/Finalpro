<?php
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['add'])) {
        // รับข้อมูลจากฟอร์มเพิ่มห้อง
        $document_form_data = $_POST['document_form_data'];

        try {
            // SQL สำหรับเพิ่มข้อมูลลงในตาราง room
            $sql = "INSERT INTO document_form (document_form_data) VALUES (:document_form_data)";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_form_data', $document_form_data);
            $stmt->execute();

            echo "เพิ่มข้อมูลแบบฟอร์มเรียบร้อยแล้ว!";
            header("Location: adform.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['edit'])) {
        // รับข้อมูลจากฟอร์มแก้ไขห้อง
        $document_form_id = $_POST['document_form_id'];
        $document_form_data = $_POST['document_form_data'];

        try {
            // อัปเดตข้อมูลห้องในฐานข้อมูล
            $sql = "UPDATE document_form SET document_form_data = :document_form_data WHERE document_form_id = :document_form_id";
            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':document_form_data', $document_form_data);
            $stmt->bindParam(':document_form_id', $document_form_id);
            $stmt->execute();

            echo "อัปเดตข้อมูลแบบฟอร์มเรียบร้อยแล้ว!";
            header("Location: adform.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    } elseif (isset($_POST['action']) && $_POST['action'] == 'delete') {
        // รับข้อมูลสำหรับการลบ
        $document_form_id = $_POST['document_form_id'];

        try {

            // Delete related records in booking_and_document table
            $delete_related_sql = "DELETE FROM booking_and_document WHERE document_form_id = :document_form_id";
            $delete_related_stmt = $conn->prepare($delete_related_sql);
            $delete_related_stmt->bindParam(':document_form_id', $document_form_id);
            $delete_related_stmt->execute();
            
            // ลบการจองออกจากฐานข้อมูล
            $delete_sql = "DELETE FROM document_form WHERE document_form_id = :document_form_id LIMIT 1";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bindParam(':document_form_id', $document_form_id);
            $delete_stmt->execute();

            echo "ลบข้อมูลแบบฟอร์มเรียบร้อยแล้ว!";
            header("Location: adform.php");
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }

    // ปิดการเชื่อมต่อ
    $conn = null;
}