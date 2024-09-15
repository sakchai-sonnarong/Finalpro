<?php
include('config/conndb.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // $start_date = $_POST['start_date'];
    // $end_date = $_POST['end_date'];
    $weekday = $_POST['weekdays'];
    $students = $_POST['students'] ?? []; // Default to an empty array if 'students' is not set

    if (!empty($weekday) && !empty($students)) {//!empty($start_date) && !empty($end_date) && 
        $conn->beginTransaction();

        try {
            // Fetch shift_id based on shift_name
            $stmt = $conn->prepare("SELECT shift_id FROM shift_schedule WHERE shift_name = :shift_name");
            $stmt->execute([':shift_name' => $weekday]);
            $shift_ids = $stmt->fetchAll(PDO::FETCH_COLUMN);

            if (empty($shift_ids)) {
                throw new Exception("ไม่พบ shift_id สำหรับวันและเวรที่เลือก.");
            }

            foreach ($shift_ids as $shift_id) {
                foreach ($students as $student_id) {
                    // Check if the combination already exists
                    $checkStmt = $conn->prepare("SELECT COUNT(*) FROM shift_student_assignment WHERE shift_id = :shift_id AND member_student_id = :member_id_student");
                    $checkStmt->execute([':shift_id' => $shift_id, ':member_id_student' => $student_id]);
                    $exists = $checkStmt->fetchColumn();

                    if (!$exists) {
                        // Insert only if the combination does not exist
                        $stmt = $conn->prepare("INSERT INTO shift_student_assignment (shift_id, member_student_id) VALUES (:shift_id, :member_id_student)");
                        $stmt->execute([':shift_id' => $shift_id, ':member_id_student' => $student_id]);
                    }
                }
            }

            $conn->commit();
            echo "<script>
                    alert('นิสิตถูกเพิ่มเข้าเวรที่เลือกเรียบร้อยแล้ว!');
                    window.history.back();
                </script>";
        } catch (Exception $e) {
            $conn->rollBack();
            echo "<script>
                    alert('เกิดข้อผิดพลาด: " . $e->getMessage() . "');
                    window.history.back();
                </script>";
        }
    } else {
        echo "<script>
                alert('โปรดเลือกวันที่ วันในสัปดาห์ และนิสิตอย่างน้อยหนึ่งรายการ.');
                window.history.back();
              </script>";
    }
}
