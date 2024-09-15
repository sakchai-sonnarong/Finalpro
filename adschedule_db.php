<?php
// นำเข้าไฟล์การเชื่อมต่อฐานข้อมูล
require_once 'config/conndb.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // รับค่าจากฟอร์ม
    $teacherId = $_POST['teacher'];
    $startDate = new DateTime($_POST['start_date']);
    $endDate = new DateTime($_POST['end_date']);
    $shiftType = $_POST['shift_type'];
    $selectedWeekdays = $_POST['weekdays'];

    // ตรวจสอบว่าค่าที่รับมาทุกฟิลด์ไม่เป็นค่าว่าง
    if (empty($teacherId) || empty($startDate) || empty($endDate) || empty($shiftType) || empty($selectedWeekdays)) {
        echo "กรุณากรอกข้อมูลให้ครบทุกฟิลด์";
        exit();
    }

    // กำหนดเวลาเวรตามที่ผู้ใช้เลือก
    $shiftTimes = [
        'morning' => ['name' => 'morning', 'start' => '08:00:00', 'end' => '12:00:00'],
        'afternoon' => ['name' => 'afternoon', 'start' => '13:00:00', 'end' => '18:00:00']
    ];

    try {

        // เตรียมคำสั่ง SQL เพื่อใช้ในการตรวจสอบว่ามีการมอบหมายงานในวันที่และช่วงเวลานั้นๆ แล้วหรือยัง
        $check_sql = "SELECT COUNT(*) FROM shift_schedule 
                    WHERE shift_date = :shift_date AND shift_time_in = :shift_time_in AND shift_time_out = :shift_time_out";

        $check_stmt = $conn->prepare($check_sql);


        // เตรียมคำสั่ง SQL เพื่อใช้ในการ INSERT
        $insert_sql = "INSERT INTO `shift_schedule` (`shift_name`, `shift_date`, `shift_time_in`, `shift_time_out`, `member_id_teacher`) 
                    VALUES (:shift_name, :shift_date, :shift_time_in, :shift_time_out, :member_id_teacher)";
        $insert_stmt = $conn->prepare($insert_sql);

        // วนลูปผ่านแต่ละวันในช่วงวันที่ที่กำหนด
        while ($startDate <= $endDate) {
            // ตรวจสอบว่าเป็นวันจันทร์ถึงศุกร์ที่ผู้ใช้เลือก
            if (in_array($startDate->format('l'), $selectedWeekdays)) {
                $shiftName = $startDate->format('l') . $shiftTimes[$shiftType]['name'];  // เช่น "Mondayเช้า"
                $shiftDate = $startDate->format('Y-m-d');
                $shiftTimeIn = $shiftTimes[$shiftType]['start'];
                $shiftTimeOut = $shiftTimes[$shiftType]['end'];

                // ตรวจสอบว่ามีการมอบหมายงานในวันที่และช่วงเวลานั้นๆ แล้วหรือยัง
                $check_stmt->execute([
                    ':shift_date' => $shiftDate,
                    ':shift_time_in' => $shiftTimeIn,
                    ':shift_time_out' => $shiftTimeOut
                ]);
                $count = $check_stmt->fetchColumn();
                if ($count > 0) {
                    // มีการมอบหมายงานแล้ว
                    echo "<script>alert('มีอาจารย์ประจำเวรในวันที่และช่วงเวลานี้แล้ว'); window.location.href='adschedule.php';</script>";
                } else {
                    // Bind ค่าและ Execute คำสั่ง SQL เพื่อ INSERT
                    $insert_stmt->execute([
                        ':shift_name' => $shiftName,
                        ':shift_date' => $shiftDate,
                        ':shift_time_in' => $shiftTimeIn,
                        ':shift_time_out' => $shiftTimeOut,
                        ':member_id_teacher' => $teacherId
                    ]);
                }
            }

            // ไปยังวันถัดไป
            $startDate->modify('+1 day');
        }
        echo "<script>alert('Shifts have been assigned successfully.'); window.location.href='adschedule.php';</script>";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
