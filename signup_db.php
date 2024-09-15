<?php

session_start();
require_once 'config/conndb.php';

if (isset($_POST['signup'])) {
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $typemem = $_POST['typemem'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $conpassword = $_POST['conpassword'];

    if (empty($firstname)) {
        $_SESSION['error'] = 'กรุณากรอกชื่อ';
        header("location: signup.php");
    } else if (empty($lastname)) {
        $_SESSION['error'] = 'กรุณากรอกนามสกุล';
        header("location: signup.php");
    } else if (empty($age)) {
        $_SESSION['error'] = 'กรุณากรอกอายุ';
        header("location: signup.php");
    } else if (empty($gender)) {
        $_SESSION['error'] = 'กรุณาระบุเพศ';
        header("location: signup.php");
    } else if (empty($phone)) {
        $_SESSION['error'] = 'กรุณากรอกเบอร์โทร';
        header("location: signup.php");
    } else if (empty($email)) {
        $_SESSION['error'] = 'กรุณากรอกอีเมล';
        header("location: signup.php");
    } else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['error'] = 'รูปแบบอีเมลไม่ถูกต้อง';
        header("location: signup.php");
    } else if (empty($typemem)) {
        $_SESSION['error'] = 'กรุณากรอกระบุประเภทสมาชิก';
        header("location: signup.php");
    } else if (empty($username)) {
        $_SESSION['error'] = 'กรุณากรอก Username';
        header("location: signup.php");
    } else if (empty($password)) {
        $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
        header("location: signup.php");
    } else if (empty($conpassword)) {
        $_SESSION['error'] = 'กรุณายืนยันรหัสผ่าน';
        header("location: signup.php");
    } else if ($password != $conpassword) {
        $_SESSION['error'] = 'รหัสผ่านไม่ตรงกัน';
        header("location: signup.php");
    } else {
        try {

            $chack_username = $conn->prepare("SELECT member_username FROM member WHERE member_username = :username");
            $chack_username->bindParam(":username", $username);
            $chack_username->execute();
            $row = $chack_username->fetch(PDO::FETCH_ASSOC);

            if ($row && $row['member_username'] == $username) {
                $_SESSION['warning'] = "มีชื่อผู้ใช้นี้ในระบบแล้ว <a href='index.php'>คลิกที่นี่</a>";
                header('location: signup.php');
            } else if (!isset($_SESSION['error'])) {
                $passwordHash = password_hash($password, PASSWORD_DEFAULT);
                // Insert into database with or without profile image
                $stmt = $conn->prepare("INSERT INTO member(member_username, member_password, member_name, member_lastname, member_gender, member_age, member_email, member_phone, member_type_id)
                                        VALUES(:username, :password, :firstname, :lastname, :gender, :age, :email, :phone, :typemem)");
                $stmt->bindParam(":username", $username);
                $stmt->bindParam(":password", $passwordHash);
                $stmt->bindParam(":firstname", $firstname);
                $stmt->bindParam(":lastname", $lastname);
                $stmt->bindParam(":gender", $gender);
                $stmt->bindParam(":age", $age);
                $stmt->bindParam(":email", $email);
                $stmt->bindParam(":phone", $phone);
                $stmt->bindParam(":typemem", $typemem);
                // $stmt->bindParam(":profile_image", $target_file);
                $stmt->execute();
                $_SESSION['success'] = "สมัครสมาชิกเรียบร้อย <a href='index.php' class='alert-link'>คลิ้กที่นี่</a> เพื่อเข้าสู่ระบบ";
                header("location: signup.php");
            } else {
                $_SESSION['error'] = "มีบางอย่างผิดปกติ";
                header("location: signup.php");
            }
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
    }
}
?>