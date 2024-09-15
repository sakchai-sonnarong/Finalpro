<?php
session_start();
require_once 'config/conndb.php';

// ตรวจสอบว่าผู้ใช้งานเข้าสู่ระบบหรือไม่
if (!isset($_SESSION['admin_login']) && !isset($_SESSION['teacher_login']) && !isset($_SESSION['student_login']) && !isset($_SESSION['user_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

$memberid = isset($_SESSION['admin_login']) ? $_SESSION['admin_login'] : (isset($_SESSION['teacher_login']) ? $_SESSION['teacher_login'] : (isset($_SESSION['student_login']) ? $_SESSION['student_login'] : (isset($_SESSION['user_login']) ? $_SESSION['user_login'] : null)));

if ($memberid === null) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

if (isset($_POST['submit'])) {
    $memberid = $_POST['member_id'];
    $membername = $_POST['member_name'];
    $memberlastname = $_POST['member_lastname'];
    $memberage = $_POST['member_age'];
    $membergender = $_POST['member_gender'];
    $memberphone = $_POST['member_phone'];
    $memberemail = $_POST['member_email'];

    if (!filter_var($memberemail, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("location: editprofile.php");
    } else {
        try {
            // สร้าง SQL สำหรับอัปเดตข้อมูล
            $sql = "UPDATE member 
                    SET member_name = :membername, 
                        member_lastname = :memberlastname, 
                        member_age = :memberage, 
                        member_gender = :membergender, 
                        member_phone = :memberphone, 
                        member_email = :memberemail 
                    WHERE member_id = :memberid";

            $stmt = $conn->prepare($sql);
            $stmt->bindParam(':membername', $membername);
            $stmt->bindParam(':memberlastname', $memberlastname);
            $stmt->bindParam(':memberage', $memberage);
            $stmt->bindParam(':membergender', $membergender);
            $stmt->bindParam(':memberphone', $memberphone);
            $stmt->bindParam(':memberemail', $memberemail);
            $stmt->bindParam(':memberid', $memberid);

            if ($stmt->execute()) {
                $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
                header("Location: profile.php");
            } else {
                $_SESSION['error'] = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
                header("Location: editprofile.php");
            }
            exit();
        } catch (PDOException $e) {
            $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
            header("Location: editprofile.php");
            exit();
        }
    }
}