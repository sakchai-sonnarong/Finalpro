<?php
// ส่วนนี้ใช้ดึงข้อมูลเวรและนิสิตจากฐานข้อมูล
session_start();
require_once 'config/conndb.php';

if (!isset($_SESSION['admin_login'])) {
    $_SESSION['error'] = 'กรุณาเข้าสู่ระบบ';
    header("Location: index.php");
    exit();
}

// ดึงข้อมูลนิสิต
$stmt_students = $conn->query("SELECT member_id, member_name FROM member WHERE member_type_id = 1");
$students = $stmt_students->fetchAll(PDO::FETCH_ASSOC);

// รับค่าจาก query parameters
$day_name = isset($_GET['day_name']) ? $_GET['day_name'] : '';
$shift_period = isset($_GET['shift_period']) ? $_GET['shift_period'] : '';
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- <link rel="stylesheet" href="css/slect-std.css"> -->
    <link rel="stylesheet" href="css/adsselect-std.css">
    <title>เพิ่มนิสิตเข้าเวร</title>

</head>

<body>
    <!-- Header Logo -->
    <header>
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
    </header>

    <main>
        <div id="container">
            <div class="head">
                <a href="stdviewshift.php" class="bi bi-arrow-left" id="BackBtn"></a>
                <h1>เพิ่มนิสิตเข้าเวร</h1>
            </div>

            <form method="post" action="std_assignment_db.php"> 
                <div class="custome-select">
                <label for="shifts">เวร :</label>
                    <select name="weekdays" id="weekdays">
                        <option value="<?= htmlspecialchars($day_name) . htmlspecialchars($shift_period) ?>"><?= htmlspecialchars($day_name) . htmlspecialchars($shift_period) ?></option>
                    </select>
                </div>

                <div class="detail-std">                 
                    <label for="students">เลือกนิสิต :</label>
                    <select type="hidden" name="students[]" id="students" multiple required></select>
                </div>
                <div class="foot-btn">
                    <button type="submit">เพิ่มนิสิตเข้าเวร</button>
                    <button type="button" id="remove-student" >ลบ</button>
                </div>
            </form>
        </div>
    </main>



    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#students').select2({
                placeholder: "ค้นหานิสิต",
                minimumInputLength: 1,
                ajax: {
                    url: 'search_std.php',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            q: params.term,
                            shift_id: $('#weekdays').val()
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                },
                allowClear: true,
            });

            // ปุ่ม "Remove" ลบชื่อที่เลือก
            $('#remove-student').on('click', function() {
                var selectedValues = $('#students').val();
                if (selectedValues.length > 0) {
                    // ลบชื่อที่เลือกจาก Select2
                    $('#students').find(':selected').remove();
                    // รีเซ็ต Select2 เพื่อให้ตัวเลือกที่ลบหายไป
                    $('#students').val(null).trigger('change');
                } else {
                    alert('กรุณาเลือกนิสิตที่ต้องการลบ');
                }
            });
        });
    </script>
</body>

</html>