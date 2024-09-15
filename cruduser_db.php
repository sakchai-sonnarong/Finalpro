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
        $member_name = $_POST['member_name'];
        $member_lastname = $_POST['member_lastname'];
        $typemem = $_POST['typemem'];
        $username = $_POST['username'];
        $password = $_POST['password'];
        $conpassword = $_POST['conpassword'];

        if (empty($member_name)) {
            $_SESSION['error'] = 'กรุณากรอกชื่อ';
            header("location: adduser.php");
            exit();
        } else if (empty($member_lastname)) {
            $_SESSION['error'] = 'กรุณากรอกนามสกุล';
            header("location: adduser.php");
            exit();
        } else if (empty($typemem)) {
            $_SESSION['error'] = 'กรุณากรอกระบุประเภทสมาชิก';
            header("location: adduser.php");
            exit();
        } else if (empty($username)) {
            $_SESSION['error'] = 'กรุณากรอก Username';
            header("location: adduser.php");
            exit();
        } else if (empty($password)) {
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: adduser.php");
            exit();
        } else if (empty($conpassword)) {
            $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
            header("location: adduser.php");
            exit();
        } else if ($password != $conpassword) {
            $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
            header("location: adduser.php");
            exit();
        } else {
            try {

                $chack_username = $conn->prepare("SELECT member_username FROM member WHERE member_username = :username");
                $chack_username->bindParam(":username", $username);
                $chack_username->execute();
                $row = $chack_username->fetch(PDO::FETCH_ASSOC);

                if ($row && $row['member_username'] == $username) {
                    $_SESSION['warning'] = "มีชื่อผู้ใช้นี้ในระบบแล้ว <a href='adduser.php'>คลิกที่นี่</a>";
                    header('location: adduser.php');
                    exit();
                } else if (!isset($_SESSION['error'])) {
                    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                    // Insert into database with or without profile image
                    $stmt = $conn->prepare("INSERT INTO member(member_username, member_password, member_name, member_lastname, member_type_id)
                                            VALUES(:username, :password, :firstname, :lastname, :typemem)");
                    $stmt->bindParam(":username", $username);
                    $stmt->bindParam(":password", $passwordHash);
                    $stmt->bindParam(":firstname", $member_name);
                    $stmt->bindParam(":lastname", $member_lastname);
                    $stmt->bindParam(":typemem", $typemem);
                    $stmt->execute();
                    $_SESSION['success'] = "เพิ่มสมาชิกเรียบร้อย";
                    header("location: adduser.php");
                    exit();
                } else {
                    $_SESSION['error'] = "มีบางอย่างผิดปกติ";
                    header("location: adduser.php");
                    exit();
                }
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    } elseif (isset($_POST['edit'])) {
        $memberid = $_POST['member_id'];
        $membername = $_POST['member_name'];
        $memberlastname = $_POST['member_lastname'];
        $memberage = $_POST['member_age'];
        $membergender = $_POST['member_gender'];
        $memberphone = $_POST['member_phone'];
        $memberemail = $_POST['member_email'];
        $memberusername = $_POST['member_username'];
        $memberpass = $_POST['member_password'];

        if (!filter_var($memberemail, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
            header("location: edituser.php?member_id=" . $memberid);
            unset($_SESSION['error']);
            exit();
        } else {
            try {
                // First, check for existing username if it's part of the form
                $chack_username = $conn->prepare("SELECT member_username FROM member WHERE member_username = :username AND member_id != :memberid");
                $chack_username->bindParam(":username", $memberusername);
                $chack_username->bindParam(":memberid", $memberid); // To ensure it excludes current user ID
                $chack_username->execute();
                $row = $chack_username->fetch(PDO::FETCH_ASSOC);

                if ($row && $row['member_username'] == $username) {
                    $_SESSION['warning'] = "มีชื่อผู้ใช้นี้ในระบบแล้ว <a href='edituser.php'>คลิกที่นี่</a>";
                    header("location: edituser.php?member_id=" . $memberid);
                    unset($_SESSION['warning']);
                    exit();
                } else {
                    // Prepare SQL update query
                    $sql = "UPDATE member 
                            SET member_name = :membername, 
                                member_lastname = :memberlastname, 
                                member_age = :memberage, 
                                member_gender = :membergender, 
                                member_phone = :memberphone, 
                                member_email = :memberemail";

                    // If the user provided a new password, include it in the update
                    if (!empty($memberpass)) {
                        $passwordHash = password_hash($memberpass, PASSWORD_DEFAULT);
                        $sql .= ", member_password = :memberpass";
                    }

                    $sql .= " WHERE member_id = :memberid";

                    $stmt = $conn->prepare($sql);
                    $stmt->bindParam(':membername', $membername);
                    $stmt->bindParam(':memberlastname', $memberlastname);
                    $stmt->bindParam(':memberage', $memberage);
                    $stmt->bindParam(':membergender', $membergender);
                    $stmt->bindParam(':memberphone', $memberphone);
                    $stmt->bindParam(':memberemail', $memberemail);
                    $stmt->bindParam(':memberid', $memberid);

                    // Bind the password parameter only if it's provided
                    if (!empty($memberpass)) {
                        $stmt->bindParam(':memberpass', $passwordHash);
                    }

                    if ($stmt->execute()) {
                        $_SESSION['success'] = 'แก้ไขข้อมูลสำเร็จ';
                        header("Location: aduser.php");
                        exit();
                    } else {
                        $_SESSION['error'] = 'เกิดข้อผิดพลาดในการแก้ไขข้อมูล';
                        header("location: edituser.php?member_id=" . $memberid);
                        unset($_SESSION['error']);
                        exit();
                    }
                }
            } catch (PDOException $e) {
                $_SESSION['error'] = 'เกิดข้อผิดพลาด: ' . $e->getMessage();
                header("location: edituser.php?member_id=" . $memberid);
                unset($_SESSION['error']);
                exit();
            }
        }
    } elseif (isset($_POST['delete'])) {
        // รับข้อมูลสำหรับการลบ
        $member_id = $_POST['member_id'];

        try {
            $conn->beginTransaction();

            // ตรวจสอบว่ามีข้อมูลใน shift_schedule หรือไม่
            $check_shift_schedule = $conn->prepare("SELECT COUNT(*) FROM shift_schedule WHERE member_id_teacher = :member_id");
            $check_shift_schedule->bindParam(':member_id', $member_id);
            $check_shift_schedule->execute();
            if ($check_shift_schedule->fetchColumn() > 0) {
                // หากมี ให้ทำการลบ
                $delete_related_sql1 = "DELETE FROM shift_schedule WHERE member_id_teacher = :member_id";
                $delete_related_stmt = $conn->prepare($delete_related_sql1);
                $delete_related_stmt->bindParam(':member_id', $member_id);
                $delete_related_stmt->execute();
            }

            // ตรวจสอบว่ามีข้อมูลใน shift_student_assignment หรือไม่
            $check_shift_student = $conn->prepare("SELECT COUNT(*) FROM shift_student_assignment WHERE member_student_id = :member_id");
            $check_shift_student->bindParam(':member_id', $member_id);
            $check_shift_student->execute();
            if ($check_shift_student->fetchColumn() > 0) {
                // หากมี ให้ทำการลบ
                $delete_related_sql2 = "DELETE FROM shift_student_assignment WHERE member_student_id = :member_id";
                $delete_related_stmt2 = $conn->prepare($delete_related_sql2);
                $delete_related_stmt2->bindParam(':member_id', $member_id);
                $delete_related_stmt2->execute();
            }

            // ตรวจสอบว่ามีข้อมูลใน booking_room หรือไม่
            $check_booking_room = $conn->prepare("SELECT COUNT(*) FROM booking_room WHERE member_id = :member_id");
            $check_booking_room->bindParam(':member_id', $member_id);
            $check_booking_room->execute();
            if ($check_booking_room->fetchColumn() > 0) {
                // หากมี ให้ทำการลบ
                $delete_related_sql3 = "DELETE FROM booking_room WHERE member_id = :member_id";
                $delete_related_stmt3 = $conn->prepare($delete_related_sql3);
                $delete_related_stmt3->bindParam(':member_id', $member_id);
                $delete_related_stmt3->execute();
            }

            // ตรวจสอบว่ามีข้อมูลใน service_request หรือไม่
            $check_service_request = $conn->prepare("SELECT COUNT(*) FROM service_request WHERE member_id_request = :member_id");
            $check_service_request->bindParam(':member_id', $member_id);
            $check_service_request->execute();
            if ($check_service_request->fetchColumn() > 0) {
                // หากมี ให้ทำการลบ
                $delete_related_sql4 = "DELETE FROM service_request WHERE member_id_request = :member_id";
                $delete_related_stmt4 = $conn->prepare($delete_related_sql4);
                $delete_related_stmt4->bindParam(':member_id', $member_id);
                $delete_related_stmt4->execute();
            }

            // ตรวจสอบและอัปเดตค่าให้เป็น NULL ใน service_request (ถ้ามีข้อมูล)
            $check_member_student = $conn->prepare("SELECT COUNT(*) FROM service_request WHERE member_id_student = :member_id");
            $check_member_student->bindParam(':member_id', $member_id);
            $check_member_student->execute();
            if ($check_member_student->fetchColumn() > 0) {
                // หากมีข้อมูล ให้ทำการอัปเดต
                $sql_update = "UPDATE service_request SET member_id_student = NULL WHERE member_id_student = :member_id";
                $update_stmt = $conn->prepare($sql_update);
                $update_stmt->bindParam(':member_id', $member_id);
                $update_stmt->execute();
            }

            // ลบข้อมูลในตาราง member
            $delete_sql = "DELETE FROM member WHERE member_id = :member_id LIMIT 1";
            $delete_stmt = $conn->prepare($delete_sql);
            $delete_stmt->bindParam(':member_id', $member_id);
            $delete_stmt->execute();

            $conn->commit(); // ทำการ commit เมื่อทำงานทุกอย่างสำเร็จ
            $_SESSION['success'] = "ลบข้อมูลผู้ใช้งานเรียบร้อยแล้ว!";
            header("Location: aduser.php");
            exit();
        } catch (PDOException $e) {
            $conn->rollBack(); // ย้อนกลับหากเกิดข้อผิดพลาด
            $_SESSION['error'] = "Error: " . $e->getMessage();
            header("Location: aduser.php");
            exit();
        }
    }

    // ปิดการเชื่อมต่อ
    $conn = null;
}