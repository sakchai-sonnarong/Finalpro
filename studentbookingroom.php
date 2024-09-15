<?php
session_start();
require_once 'config/conndb.php';
if (!isset($_SESSION['student_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header('location: index.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Room</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- CSS -->
    <!-- <link rel="stylesheet" href="css/stdbookingrm.css"> -->
    <link rel="stylesheet" href="css/bookgrm-std.css">

</head>

<body>
    <header>
        <!-- === NAV BAR === -->
        <nav>
            <?php
            include("nav-std.php");
            ?>
        </nav>
        
    </header>
    <a href="javascript:void(0)" onclick="w3_open()" class="bi bi-list" id="MenuBtn"></a>

    <main>

        <div id="logo-head">
            <div class="frame">
                <div class="logo">
                    <img src="images/psylogo.png" alt="">
                </div>
                <div class="namecenter">
                    <h1>ศูนย์ความเป็นเลิศทางจิตวิทยา<br></h1>
                    <h2>PSYCHOLOGY EXCELLENCE CENTER</h2>
                    <h3>คณะศึกษาศาสตร์ มหาวิทยาลัยมหาสารคาม</h3>
                </div>
            </div>
        </div>

        <div id="container">
            <h2>กรอกข้อมูลในการจองห้อง</h2>
            <form action="studentbookingroom_db.php" method="post">
                <div class="details">
                    <?php
                    if (isset($_POST['startTime']) && isset($_POST['endTime']) && isset($_POST['room']) && isset($_POST['date'])) {
                        $startTime = $_POST['startTime'];
                        $endTime = $_POST['endTime'];
                        $room = $_POST['room'];
                        $date = $_POST['date'];

                        echo '<input type="hidden" name="startTime" value="' . htmlspecialchars($startTime) . '">';
                        echo '<input type="hidden" name="endTime" value="' . htmlspecialchars($endTime) . '">';
                        echo '<input type="hidden" name="room" value="' . htmlspecialchars($room) . '">';
                        echo '<input type="hidden" name="date" value="' . htmlspecialchars($date) . '">';

            
                        echo "<span class='detail'>วันที่จอง: " . htmlspecialchars($date) . "</span> " ;
                        echo "<span class='detail'>เวลาเริ่ม: " . htmlspecialchars($startTime) . "  " . "</span> ";
                        echo "<span class='detail'>เวลาสิ้นสุด: " . htmlspecialchars($endTime) . "</span> ";
                        echo "<span class='detail'>ห้อง: " . htmlspecialchars($room) . "<br>". "</span> ";
                    } else {
                        echo "ข้อมูลไม่ครบถ้วน";
                        exit;
                    }

                    if (isset($_SESSION['student_login'])) {
                        $student_id = $_SESSION['student_login'];
                        $stmt = $conn->query("SELECT * FROM member WHERE member_id = $student_id");
                        $stmt->execute();
                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                    }
                    ?>
                    
                </div>
                    <label for="name"> ชื่อ-นามสกุล: <?php echo $row['member_name'] . ' ' . $row['member_lastname'] ?></label>
                
                    <div class="mb-3">
                    <?php
                    $selectform = $conn->prepare('SELECT * FROM document_form');
                    $selectform->execute();
                    ?>
                </div>
                <div class="form1">
                    <label for="document_form_id1">แบบฟอร์มเอกสารที่ 1</label>
                    <select name="document_form_id1" id="document_form_id1">
                        <option selected disabled value="">เลือกเอกสาร</option>
                        <?php while ($row_type = $selectform->FETCH(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row_type['document_form_id'] ?> "><?php echo $row_type['document_form_data'] ?> </option>
                        <?php } ?>
                    </select>
                    <label for="number1" class="form-label">จำนวน</label>
                    <input type="number" class="form-control" name="number1" aria-describedby="number1">
                </div>
                <div class="mb-3">
                    <?php
                    $selectform = $conn->prepare('SELECT * FROM document_form');
                    $selectform->execute();
                    ?>
                </div>
                <div class="form2">
                    <label for="document_form_id2">แบบฟอร์มเอกสารที่ 2</label>
                    <select name="document_form_id2" id="document_form_id2">
                        <option selected disabled value="">เลือกเอกสาร</option>
                        <?php while ($row_type = $selectform->FETCH(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row_type['document_form_id'] ?> "><?php echo $row_type['document_form_data'] ?> </option>
                        <?php } ?>
                    </select>
                    <label for="number2" class="form-label">จำนวน</label>
                    <input type="number" class="form-control" name="number2" aria-describedby="number2">
                </div>
                <div class="mb-3">
                    <?php
                    $selectform = $conn->prepare('SELECT * FROM document_form');
                    $selectform->execute();
                    ?>
                </div>
                <div class="form3">
                    <label for="document_form_id3">แบบฟอร์มเอกสารที่ 3</label>
                    <select name="document_form_id3" id="document_form_id3">
                        <option selected disabled value="">เลือกเอกสาร</option>
                        <?php while ($row_type = $selectform->FETCH(PDO::FETCH_ASSOC)) { ?>
                            <option value="<?php echo $row_type['document_form_id'] ?> "><?php echo $row_type['document_form_data'] ?> </option>
                        <?php } ?>
                    </select>
                    <label for="number3" class="form-label">จำนวน</label>
                    <input type="number" class="form-control" name="number3" aria-describedby="number3">
                </div>
                <div class="form4">
                    <label for="details">รายละเอียดการจอง</label>
                    <textarea type="text" class="form-control" name="details" aria-describedby="details" required
                                oninvalid="this.setCustomValidity('กรุณากรอกรายละเอียดการจอง')" 
                                oninput="this.setCustomValidity('')"></textarea>
                </div>
                <button type="submit" name="submit" class="btn btn-primary">ยืนยันการจอง</button>
            </form>
        </div>
    </main>

    <!-- ==== Main JS==== -->
    <script src="maintest.js"></script>
</body>

</html>