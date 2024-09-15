<?php 

    session_start();
    require_once 'config/conndb.php';

    if(isset($_POST['signin'])){
        $username = $_POST['username'];
        $password = $_POST['password'];
        

        if(empty($username && $password)){
            $_SESSION['error'] = 'กรุณากรอก Username และ Password';
            header("location: index.php");
        }else if(empty($username)){
            $_SESSION['error'] = 'กรุณากรอก Username';
            header("location: index.php");
        }else if(empty($password)){
            $_SESSION['error'] = 'กรุณากรอกรหัสผ่าน';
            header("location: index.php");
        }else{
            try{
                require_once 'config/conndb.php';

                $check_data = $conn->prepare("SELECT * FROM member WHERE member_username = :username");
                $check_data->bindParam(":username",$username);
                $check_data->execute();
                $row = $check_data->fetch(PDO::FETCH_ASSOC);
                $hashedpassword = $row['member_password'];

                if($check_data->rowCount() > 0){
                    if($username == $row['member_username']){
                        if(password_verify($password, $hashedpassword)){//เช็ครหัสผ่าน
                            $_SESSION['member_id'] = $row['member_id'];
                            if($row['member_type_id'] == 4){//ตรวจสอบระดับของสมาชิก
                                $_SESSION['admin_login'] = $row['member_id'];
                                header(("location: admin.php"));
                            }else if($row['member_type_id'] == 1){//ตรวจสอบระดับของสมาชิก
                                $_SESSION['student_login'] = $row['member_id'];
                                header(("location: teststd.php"));//ไปที่หน้าของstudentshowmybooking.php
                            }else if($row['member_type_id'] == 2){//ตรวจสอบระดับของสมาชิก
                                $_SESSION['teacher_login'] = $row['member_id'];
                                header(("location: teacher.php"));//ไปที่หน้าของteachershowbookingroom.php
                            }else{//ตรวจสอบระดับของสมาชิก
                                $_SESSION['user_login'] = $row['member_id'];
                                header(("location: user.php"));//ไปหน้าผู้ใช้ทั่วไป
                            }
                        }else{
                            $_SESSION['error'] = 'รหัสผ่านไม่ถูกต้อง';
                            header("location: index.php");
                        }
                    }else{
                        $_SESSION['error'] = 'ชื่อผู้ใช้ไม่ถูกต้อง';
                        header("location: index.php");
                    }
                }else{
                    $_SESSION['error'] = "ไม่มีข้อมูลในระบบ";
                    header("location: index.php");
                }
                
            }catch(PDOException $e){
                echo $e->getMessage();
            }
        }

    }

?>